import View from "../views/indexView.class.js";
import Servicios from "../models/servicios.class.js";
import Categorias from "../models/categorias.class.js";
import Usuarios from "../models/usuarios.class.js";
export default class Controller {

    constructor() {
        this.model = {
            servicios: new Servicios(),
            categorias: new Categorias(),
            usuarios: new Usuarios()
            //origenes: new Origins()
        }
        this.view = new View();
    }

    async init() {
        console.log("✅ Controller iniciado");

        try {

            await Promise.all([
                this.model.servicios.populate(),
                this.model.categorias.populate(),
                this.model.usuarios.populate()
            ]);

            this.model.servicios.data.forEach(servicio =>
                this.view.renderNewServicio(servicio, this.model.categorias.data, this.model.usuarios.data));

            this.view.sliceCards();

        } catch (error) {
            this.view.mostrarErrores("Error al cargar la infromación inicial: " + error);
        }

    }
}