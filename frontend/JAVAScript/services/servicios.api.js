const SERVER = 'http://localhost:3000';

async function getDBServicios() {
    const resp = await fetch(`${SERVER}/Servicio`);

    if (!resp.ok) {
        const texto = await resp.text(); // si hay mensaje de error
        console.error("Respuesta del servidor:", texto);
        throw new Error(`Fallo al obtener todos los Servicios: ${resp.status}`);
    }
    return await resp.json();
}

async function getDBServicio(idServicio) {
    const res = await fetch(`${SERVER}/Servicio/${idServicio}`);
    return await res.json();
}

async function getDBServicioCategoria(idCategoria) {
    const res = await fetch(`${SERVER}/Servicio?IDCategoria=${idCategoria}`);
    return await res.json();
}

async function addDBServicio(servicio) {
    let response = await fetch(`${SERVER}/Servicio/`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(servicio)
    });
    if (!response.ok) throw new Error("Error al guardar añadir un servicio");
    return await response.json();
}

async function removeDBServicio(idServicio) {
    let response = await fetch(`${SERVER}/Servicio/${idServicio}`, {
        method: 'DELETE',
        headers: { 'Content-Type': 'application/json' }
    });
    if (!response.ok) throw new Error("Ha habido un error al borrar el Coche");
    return await response.json();
}

async function changeDBServicio(couMod) {
    let { id } = couMod;
    let response = await fetch(`${SERVER}/Servicio/${id}`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(couMod)
    })
    if (!response.ok) throw new Error("Ha habido un error al actualizar los datosde del servicio");
    return await response.json();
}

async function modifiServicioCampoDB(id, cambios) {
    console.log("id" + id + "cambios realizado" + cambios)
    let response = await fetch(`${SERVER}/Servicio/${id}`, {
        method: 'PATCH',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(cambios)
    });

    if (!response.ok) throw new Error("Error al actualizar parcialmente el servicio");

    return await response.json();
}

async function modifiServicioDBServicio(servicio) {
    let id = servicio.id;
    let response = await fetch(`${SERVER}/Servicio/${id}`, {
        method: 'PATCH',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(servicio)
    });

    if (!response.ok) throw new Error("Error al actualizar parcialmente el servicio");

    return await response.json();
}

export {
    getDBServicios,
    getDBServicio,
    addDBServicio,
    removeDBServicio,
    changeDBServicio,
    modifiServicioDBServicio,
    getDBServicioCategoria,
    modifiServicioCampoDB
};