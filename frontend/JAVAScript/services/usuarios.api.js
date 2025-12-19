const SERVER = 'http://localhost:3000';

async function getDBUsuarios() {
    const resp = await fetch(`${SERVER}/Usuarios`);

    if (!resp.ok) {
        const texto = await resp.text(); // si hay mensaje de erro
        console.error("Respuesta del servidor:", texto);
        throw new Error(`Fallo al obtener todos los Usuarios: ${resp.status}`);
    }
    return await resp.json();
}

async function getDBUsuario(idUsuario) {
    const res = await fetch(`${SERVER}/Usuarios/${idUsuario}`);
    return await res.json();
}

async function addDBUsuario(usuario) {
    let response = await fetch(`${SERVER}/Usuarios/`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(usuario)
    });
    if (!response.ok) throw new Error("Error al guardar añadir un usuario");
    return await response.json();
}

async function removeDBUsuario(idUsuario) {
    let response = await fetch(`${SERVER}/Usuarios/${idUsuario}`, {
        method: 'DELETE',
        headers: { 'Content-Type': 'application/json' }
    });
    if (!response.ok) throw new Error("Ha habido un error al borrar el Usuario");
    return await response.json();
}

async function changeDBUsuario(couMod) {
    let { id } = couMod;
    let response = await fetch(`${SERVER}/Usuarios/${id}`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(couMod)
    })
    if (!response.ok) throw new Error("Ha habido un error al actualizar los datosde del usuario");
    return await response.json();
}

async function modifiUsuarioCampoDB(id, cambios) {
    console.log("id" + id + "cambios realizado" + cambios)
    let response = await fetch(`${SERVER}/Usuarios/${id}`, {
        method: 'PATCH',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(cambios)
    });

    if (!response.ok) throw new Error("Error al actualizar parcialmente el usuario");

    return await response.json();
}

async function modifiUsuarioDBUsuario(usuario) {
    let id = usuario.id;
    let response = await fetch(`${SERVER}/Usuarios/${id}`, {
        method: 'PATCH',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(usuario)
    });

    if (!response.ok) throw new Error("Error al actualizar parcialmente el usuario");

    return await response.json();
}

export {
    getDBUsuarios,
    getDBUsuario,
    addDBUsuario,
    removeDBUsuario,
    changeDBUsuario,
    modifiUsuarioDBUsuario,
    modifiUsuarioCampoDB
};