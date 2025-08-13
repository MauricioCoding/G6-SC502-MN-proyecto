<?php
require_once __DIR__ . '/../config/conexion.php';

class EmpleadosModel
{
    private $conn;

    public function __construct()
    {
        $this->conn = getPDOConnection(); 
    }

public function listarEmpleados($id_tipo_usuario = null)
{
    if ($id_tipo_usuario !== null) {
        $stmt = $this->conn->prepare("
            SELECT 
                u.cedula,
                u.nombre,
                u.primer_apellido,
                u.segundo_apellido,
                u.nombre_usuario,
                u.correo,
                u.fecha_nacimiento,
                u.fecha_registro,
                u.salario,
                u.id_puesto_usuario AS id_puesto_usuario,
                p.nombre_puesto             AS puesto_nombre,
                u.id_tipo_usuario,
                tu.nombre            AS tipo_nombre,   -- ajusta si el campo se llama distinto
                u.id_rol_usuario AS id_rol_usuario,
                r.rol             AS rol_nombre,    -- ajusta si el campo se llama distinto
                u.id_estado,
                e.estado             AS estado_nombre
            FROM FIDE_USUARIOS_TB u
            LEFT JOIN FIDE_PUESTO_TB       p  ON u.id_puesto_usuario = p.id_puesto
            LEFT JOIN FIDE_TIPO_USUARIO_TB tu ON u.id_tipo_usuario   = tu.id_tipo_usuario
            LEFT JOIN FIDE_ROL_TB          r  ON u.id_rol_usuario    = r.id_rol
            LEFT JOIN FIDE_ESTADOS_TB      e  ON u.id_estado         = e.id_estado
            WHERE u.id_tipo_usuario = :tipo
                AND UPPER(e.estado) = 'Activo'
            ORDER BY u.nombre, u.primer_apellido
        ");
        $stmt->bindParam(':tipo', $id_tipo_usuario, PDO::PARAM_INT);
    } else {
        $stmt = $this->conn->prepare("
            SELECT 
                u.cedula,
                u.nombre,
                u.primer_apellido,
                u.segundo_apellido,
                u.nombre_usuario,
                u.correo,
                u.fecha_nacimiento,
                u.fecha_registro,
                u.salario,
                u.id_puesto_usuario,
                p.nombre_puesto            AS puesto_nombre,
                u.id_tipo_usuario,
                tu.nombre            AS tipo_nombre,   -- ajusta si el campo se llama distinto
                u.id_rol_usuario,
                r.rol             AS rol_nombre,    -- ajusta si el campo se llama distinto
                u.id_estado,
                e.estado             AS estado_nombre
            FROM FIDE_USUARIOS_TB u
            LEFT JOIN FIDE_PUESTO_TB       p  ON u.id_puesto_usuario = p.id_puesto
            LEFT JOIN FIDE_TIPO_USUARIO_TB tu ON u.id_tipo_usuario   = tu.id_tipo_usuario
            LEFT JOIN FIDE_ROL_TB          r  ON u.id_rol_usuario    = r.id_rol
            LEFT JOIN FIDE_ESTADOS_TB      e  ON u.id_estado         = e.id_estado
            WHERE UPPER(e.estado) = 'ACTIVO'
            ORDER BY u.nombre, u.primer_apellido
        ");
    }

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


public function insertarEmpleado(array $data): bool
{

    $sql = "INSERT INTO FIDE_USUARIOS_TB (
                cedula,
                nombre,
                primer_apellido,
                segundo_apellido,
                nombre_usuario,
                contrasea,           
                correo,
                fecha_nacimiento,
                fecha_registro,
                salario,
                id_puesto_usuario,
                id_tipo_usuario,     
                id_rol_usuario,
                id_estado
            ) VALUES (
                ?, ?, ?, ?, ?, ?, ?, 
                STR_TO_DATE(NULLIF(?, ''), '%Y-%m-%d'),
                NOW(),
                ?, ?, 
                1,                  
                ?, ?
            )";

    $st = $this->conn->prepare($sql);

    try {
        return $st->execute([
            $data['cedula'],
            $data['nombre'],
            $data['primer_apellido'],
            $data['segundo_apellido'],
            $data['nombre_usuario'],
            $data['contrasena'],
            $data['correo'],
            $data['fecha_nacimiento'],  
            $data['salario'],
            $data['id_puesto_usuario'],
            $data['id_rol_usuario'],
            $data['id_estado']
        ]);
    } catch (PDOException $e) {
    $sqlState = $e->getCode();
    $driverErr = $e->errorInfo[1] ?? null;   
    $msgRaw   = $e->errorInfo[2] ?? $e->getMessage();

    if ($sqlState === '23000') {
        if ($driverErr === 1062) {
           
            $campo = 'desconocido';
            if (stripos($msgRaw, 'cedula') !== false || stripos($msgRaw, 'PRIMARY') !== false) $campo = 'cedula';
            elseif (stripos($msgRaw, 'nombre_usuario') !== false) $campo = 'nombre_usuario';
            elseif (stripos($msgRaw, 'correo') !== false) $campo = 'correo';
            elseif (stripos($msgRaw, 'contrasea') !== false) $campo = 'contraseña';

            throw new Exception("Dato duplicado en '$campo'. Detalle: " . $msgRaw);
        }
        if ($driverErr === 1452) {

            throw new Exception("Violación de clave foránea. Revisa que existan los IDs referenciados (puesto/rol/estado). Detalle: " . $msgRaw);
        }
    }

    throw new Exception('Error al insertar empleado: ' . $msgRaw);
    }
}


public function actualizarEmpleado(array $data): bool
{

    $set = [
        "nombre = ?",
        "primer_apellido = ?",
        "segundo_apellido = ?",
        "nombre_usuario = ?",
        "correo = ?",
        "fecha_nacimiento = STR_TO_DATE(?, '%Y-%m-%d')",
        "salario = ?",
        "id_puesto_usuario = ?",
        "id_tipo_usuario = 1",       
        "id_rol_usuario = ?",
        "id_estado = ?"
    ];

    $params = [
        $data['nombre'],
        $data['primer_apellido'],
        $data['segundo_apellido'],
        $data['nombre_usuario'],
        $data['correo'],
        $data['fecha_nacimiento'],
        $data['salario'],
        $data['id_puesto_usuario'],
        $data['id_rol_usuario'],
        $data['id_estado']
    ];

    if (isset($data['contrasena']) && trim($data['contrasena']) !== '') {
        $set[] = "contrasea = ?";
        $params[] = $data['contrasena']; 
    }

    $sql = "UPDATE FIDE_USUARIOS_TB
               SET " . implode(", ", $set) . "
             WHERE cedula = ?";

    $params[] = $data['cedula'];

    $stmt = $this->conn->prepare($sql);

    try {
        return $stmt->execute($params);
    } catch (PDOException $e) {
        if ($e->getCode() === '23000') {
            throw new Exception("Violación de restricción (revisa cedula/nombre_usuario/correo únicos o FKs).");
        }
        throw new Exception("Error al actualizar empleado: " . $e->getMessage());
    }
}


public function eliminarEmpleado(int $cedula): bool
{
    $sql = "UPDATE FIDE_USUARIOS_TB
               SET id_estado = 2   
             WHERE cedula = ? AND id_tipo_usuario = 1";

    $stmt = $this->conn->prepare($sql);
    return $stmt->execute([$cedula]);
}


public function obtenerEmpleadoPorCedula(int $cedula): ?array
{
    $sql = "SELECT 
                u.cedula,
                u.nombre,
                u.primer_apellido,
                u.segundo_apellido,
                u.nombre_usuario,
                u.contrasea,               
                u.correo,
                DATE_FORMAT(u.fecha_nacimiento, '%Y-%m-%d') AS fecha_nacimiento,
                DATE_FORMAT(u.fecha_registro, '%Y-%m-%d %H:%i:%s') AS fecha_registro,
                u.salario,
                u.id_puesto_usuario,
                p.nombre_puesto AS puesto_nombre,
                u.id_tipo_usuario,
                tu.nombre AS tipo_usuario_nombre,
                u.id_rol_usuario,
                r.rol AS rol_nombre,
                u.id_estado,
                e.estado AS estado_nombre
            FROM FIDE_USUARIOS_TB u
            LEFT JOIN FIDE_PUESTO_TB       p  ON u.id_puesto_usuario = p.id_puesto
            LEFT JOIN FIDE_TIPO_USUARIO_TB tu ON u.id_tipo_usuario   = tu.id_tipo_usuario
            LEFT JOIN FIDE_ROL_TB          r  ON u.id_rol_usuario    = r.id_rol
            LEFT JOIN FIDE_ESTADOS_TB      e  ON u.id_estado         = e.id_estado
            WHERE u.cedula = ? 
              AND u.id_tipo_usuario = 1
            LIMIT 1";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute([$cedula]);
    $empleado = $stmt->fetch(PDO::FETCH_ASSOC);

    return $empleado ?: null; 
}


public function listarPuestos(): array
{
    $sql = "SELECT 
                p.id_puesto,
                p.nombre_puesto,
                p.id_estado,
                e.estado AS estado_nombre
            FROM FIDE_PUESTO_TB p
            LEFT JOIN FIDE_ESTADOS_TB e 
                   ON p.id_estado = e.id_estado
            ORDER BY p.id_puesto ASC";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
}

public function listarRoles(): array
{
    $sql = "SELECT 
                r.id_rol,
                r.rol,
                r.id_estado,
                e.estado AS estado_nombre
            FROM FIDE_ROL_TB r
            LEFT JOIN FIDE_ESTADOS_TB e 
                   ON r.id_estado = e.id_estado
            ORDER BY r.id_rol ASC";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
}

}
