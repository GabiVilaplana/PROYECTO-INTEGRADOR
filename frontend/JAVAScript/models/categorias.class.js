import * as CategoriasApi from '../services/categorias.api.js';
import Categoria from './categoria.class.js';

export default class Categorias {
    constructor() {
        this.data = [];
    }
//UJH
    async populate() {
        try {
            const categorias = await CategoriasApi.getDBCategorias();
            this.data = categorias.map(s => new Categoria(s));
        } catch (error) {
            console.error("Error al cargar categorias:", error);
            throw error;
        }
    }

    async addCategoria(categoriaData) {
        try {
            const nuevoCategoria = await CategoriasApi.addDBCategoria(categoriaData);
            const categoria = new Categoria(nuevoCategoria);
            this.data.push(categoria);
            return categoria;
        } catch (error) {
            console.error("Error al añadir categoria:", error);
            throw error;
        }
    }

    async updateCategoria(categoriaData) {
        try {
            const actualizado = await CategoriasApi.changeDBCategoria(categoriaData);
            const categoria = new Categoria(actualizado);
            this.data = this.data.map(s => s.IDCategoria === categoriaData.IDCategoria ? categoria : s);
            return categoria;
        } catch (error) {
            console.error("Error al actualizar categoria:", error);
            throw error;
        }
    }

    getCategoriaById(id) {
        return this.data.find(s => s.IDCategoria === id);
    }

    /*getCategoriasByCategoria(idCategoria) {
        return this.data.filter(s => s.IDCategoria === idCategoria && s.Estado === "activo");
    }*/
}
