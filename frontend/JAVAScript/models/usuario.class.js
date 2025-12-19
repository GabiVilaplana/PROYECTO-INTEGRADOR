export default class Usuario {
    constructor(dato={}) {
        this.IDUsuario = dato.IDUsuario;
        this.Nombre = dato.Nombre;
        this.Apellidos = dato.Apellidos;
        this.Telefono = dato.Telefono;
        this.Correo = dato.Correo;
        this.Password = dato.Password;
        this.Valoracion = dato.Valoracion;
    }
//UJH

    toString() {
        return `Usuario{
      ID: ${this.IDServicio},
      Nombre: "${this.Nombre}",
      Apellidos: ${this.Apellidos},
      Telefono: ${this.Telefono},
      Correo: ${this.Correo}
      Valoracion: ${this.Valoracion}
    }`;
    }
}