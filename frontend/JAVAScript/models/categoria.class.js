export default class Categoria {
    constructor(dato={}) {
        this.IDCategoria = dato.IDCategoria;
        this.Nombre = dato.Nombre;
        this.Descripcion = dato.Descripcion;
        this.Activo = dato.Activo;
    }
//UJH

    toString() {
        return `Categoria{
      ID: ${this.IDServicio},
      Nombre: "${this.Nombre}",
      Descripció: ${this.Descripcion},
      Estado: ${this.Estado}
    }`;
    }
}