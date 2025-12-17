const SERVER = 'http://localhost:3000';

async function getDBCategorias() {
    const resp = await fetch(`${SERVER}/Categorias`);

    if (!resp.ok) {
        const texto = await resp.text(); // si hay mensaje de error
        console.error("Respuesta del servidor:", texto);
        throw new Error(`Fallo al obtener todos los Categorias: ${resp.status}`);
    }
    return await resp.json();
}

async function getDBCategoria(idCategoria) {
    const res = await fetch(`${SERVER}/Categorias/${idCategoria}`);
    return await res.json();
}

async function addDBCategoria(categoria) {
    let response = await fetch(`${SERVER}/Categorias/`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(categoria)
    });
    if (!response.ok) throw new Error("Error al guardar añadir un categoria");
    return await response.json();
}

async function removeDBCategoria(idCategoria) {
    let response = await fetch(`${SERVER}/Categorias/${idCategoria}`, {
        method: 'DELETE',
        headers: { 'Content-Type': 'application/json' }
    });
    if (!response.ok) throw new Error("Ha habido un error al borrar el Categoria");
    return await response.json();
}

async function changeDBCategoria(couMod) {
    let { id } = couMod;
    let response = await fetch(`${SERVER}/Categorias/${id}`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(couMod)
    })
    if (!response.ok) throw new Error("Ha habido un error al actualizar los datosde del categoria");
    return await response.json();
}

async function modifiCategoriaCampoDB(id, cambios) {
    console.log("id" + id + "cambios realizado" + cambios)
    let response = await fetch(`${SERVER}/Categorias/${id}`, {
        method: 'PATCH',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(cambios)
    });

    if (!response.ok) throw new Error("Error al actualizar parcialmente el categoria");

    return await response.json();
}

async function modifiCategoriaDBCategoria(categoria) {
    let id = categoria.id;
    let response = await fetch(`${SERVER}/Categorias/${id}`, {
        method: 'PATCH',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(categoria)
    });

    if (!response.ok) throw new Error("Error al actualizar parcialmente el categoria");

    return await response.json();
}

export {
    getDBCategorias,
    getDBCategoria,
    addDBCategoria,
    removeDBCategoria,
    changeDBCategoria,
    modifiCategoriaDBCategoria,
    modifiCategoriaCampoDB
};