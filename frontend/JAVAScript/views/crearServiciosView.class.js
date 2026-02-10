export default class CrearServicioView {
    constructor() {
        this.form = document.getElementById("form-crear-servicio");
        this.selectCategoria = document.getElementById("idCategoria");
    }

    // Llena el desplegable de categorías
    renderCategorias(categorias) {
        this.selectCategoria.innerHTML = '<option value="">Selecciona una categoría</option>';
        categorias.forEach(cat => {
            const option = document.createElement("option");
            option.value = cat.IDCategoria;
            option.textContent = cat.Nombre;
            this.selectCategoria.appendChild(option);
        });
    }

    // Obtiene los datos del formulario de forma limpia
    getFormData() {
        const formData = new FormData(this.form);
        return Object.fromEntries(formData.entries());
    }

    mostrarMensaje(mensaje, esExito = true) {
        alert(mensaje); // Podrías mejorar esto con un modal más adelante
    }
}