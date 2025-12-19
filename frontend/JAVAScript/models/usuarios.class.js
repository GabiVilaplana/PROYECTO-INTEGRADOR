import * as UsuariosApi from '../services/usuarios.api.js';
import Usuario from './usuario.class.js';

export default class Usuarios {
    constructor() {
        this.data = [];
    }

    async populate() {
        try {
            const usuarios = await UsuariosApi.getDBUsuarios();
            this.data = usuarios.map(s => new Usuario(s));
        } catch (error) {
            console.error("Error al cargar usuarios:", error);
            throw error;
        }
    }
//UJH
    async addUsuario(usuarioData) {
        try {
            const nuevoUsuario = await UsuariosApi.addDBUsuario(usuarioData);
            const usuario = new Usuario(nuevoUsuario);
            this.data.push(usuario);
            return usuario;
        } catch (error) {
            console.error("Error al añadir usuario:", error);
            throw error;
        }
    }

    async updateUsuario(usuarioData) {
        try {
            const actualizado = await UsuariosApi.changeDBUsuario(usuarioData);
            const usuario = new Usuario(actualizado);
            this.data = this.data.map(s => s.IDUsuario === usuarioData.IDUsuario ? usuario : s);
            return usuario;
        } catch (error) {
            console.error("Error al actualizar usuario:", error);
            throw error;
        }
    }

    getUsuarioById(id) {
        return this.data.find(s => s.IDUsuario === id);
    }

    /*getUsuariosByUsuario(idUsuario) {
        return this.data.filter(s => s.IDUsuario === idUsuario && s.Estado === "activo");
    }*/
}
