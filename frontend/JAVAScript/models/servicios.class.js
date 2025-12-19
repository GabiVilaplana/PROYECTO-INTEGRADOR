import * as ServiciosApi from '../services/servicios.api.js';
import Servicio from './servicio.class.js';

export default class Servicios {
    constructor() {
        this.data = [];
    }
//UJH
    async populate() {
        try {
            const servicios = await ServiciosApi.getDBServicios();
            this.data = servicios.map(s => new Servicio(s));
        } catch (error) {
            console.error("Error al cargar servicios:", error);
            throw error;
        }
    }

    async addServicio(servicioData) {
        try {
            const nuevoServicio = await ServiciosApi.addDBServicio(servicioData);
            const servicio = new Servicio(nuevoServicio);
            this.data.push(servicio);
            return servicio;
        } catch (error) {
            console.error("Error al añadir servicio:", error);
            throw error;
        }
    }

    async updateServicio(servicioData) {
        try {
            const actualizado = await ServiciosApi.changeDBServicio(servicioData);
            const servicio = new Servicio(actualizado);
            this.data = this.data.map(s => s.IDServicio === servicioData.IDServicio ? servicio : s);
            return servicio;
        } catch (error) {
            console.error("Error al actualizar servicio:", error);
            throw error;
        }
    }

    getServicioById(id) {
        return this.data.find(s => s.IDServicio === id);
    }

    getServiciosByCategoria(idCategoria) {
        return this.data.filter(s => s.IDCategoria === idCategoria && s.Estado === "activo");
    }
}
