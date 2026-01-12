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
            //SDFSDF
        }
        this.view = new View();
    }

    async init() {
        console.log("✅ Controller iniciado");

        try {
            this.view.renderFilterCategorias(this.handlerFilterCategorias.bind(this))

            await Promise.all([
                this.model.servicios.populate(),
                this.model.categorias.populate(),
                this.model.usuarios.populate()
            ]);

            this.model.categorias.data.forEach(categoria => {
                const numServicios = this.model.servicios.data.filter(
                    servicio => ((servicio.IDCategoria === categoria.IDCategoria) && servicio.Estado === "activo")
                ).length;
                this.view.renderNewServicioPorCategoria(categoria, numServicios);
            });


            this.model.servicios.data
                .filter(servicio => servicio.Estado === "activo")
                .forEach(servicio =>
                    this.view.renderNewServicio(servicio, this.model.categorias.data, this.model.usuarios.data
                    ));


            //this.view.sliceCards();
            this.view.bindProfileDropdown(() => {
                this.view.renderToggleDropdown();
            });

        } catch (error) {
            this.view.mostrarErrores("Error al cargar la infromación inicial: " + error);
        }

    }

    async handlerFilterCategorias(IDCategoria) {
        try {
            this.view.contenedorServicios.innerHTML = "";
            this.view.contenedorCardsCategoria.innerHTML = "";

            const serviciosFiltrados = this.model.servicios.getServiciosByCategoria(IDCategoria)
            serviciosFiltrados
                .filter(servicio => servicio.Estado === "activo")
                .forEach(servicio =>
                    this.view.renederServiciosPorCategoria(servicio, this.model.categorias.data, this.model.usuarios.data

                    ));
        } catch (error) {
            this.view.mostrarErrores("Error al cargar la infromación inicial: " + error);

        }
    }
}