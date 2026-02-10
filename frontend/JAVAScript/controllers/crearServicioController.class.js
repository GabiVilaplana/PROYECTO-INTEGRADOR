import CrearServicioView from "../views/crearServicioView.class.js";
import Categorias from "../models/categorias.class.js";

export default class CrearServicioController {
    constructor() {
        this.view = new CrearServicioView();
        this.modelCategorias = new Categorias();
    }

    async init() {
        try {
            // 1. Cargar categorías para el select
            await this.modelCategorias.populate();
            this.view.renderCategorias(this.modelCategorias.data);

            // 2. Escuchar el envío del formulario
            this.setupEventListeners();
        } catch (error) {
            console.error("Error al iniciar vista de creación:", error);
        }
    }

    setupEventListeners() {
        this.view.form.addEventListener("submit", async (e) => {
            e.preventDefault();
            const datos = this.view.getFormData();

            try {
                const response = await fetch("../backend/includes/guardarServicio.php", {
                    method: "POST",
                    body: JSON.stringify(datos),
                    headers: { "Content-Type": "application/json" }
                });

                const resultado = await response.json();
                if (resultado.success) {
                    this.view.mostrarMensaje("¡Oferta creada con éxito!");
                    window.location.href = "../../index.php"; // Volver al inicio
                } else {
                    this.view.mostrarMensaje("Error: " + resultado.message, false);
                }
            } catch (error) {
                this.view.mostrarMensaje("Error en el servidor", false);
            }
        });
    }
}