export default class Servicio {
    constructor(dato = {}) {
        this.IDServicio = dato.IDServicio;
        this.Nombre = dato.Nombre;
        this.FechaCreacion = dato.FechaCreacion;
        this.Descripcion = dato.Descripcion;
        this.DuracionEstimada = dato.DuracionEstimada;
        this.Precio = dato.Precio;
        this.IDCategoria = dato.IDCategoria;
        this.IDUsuarioCreacion = dato.IDUsuarioCreacion;
        this.IDImagen = dato.IDImagen;
        this.Estado = dato.Estado;

    }

//UJH
    toString() {
        const fecha = this.FechaCreacion instanceof Date
            ? this.FechaCreacion.toLocaleDateString('es-ES')
            : String(this.FechaCreacion);

        return `Servicio{
      ID: ${this.IDServicio},
      Nombre: "${this.Nombre}",
      Creado: ${fecha},
      Duración: ${this.DuracionEstimada} min,
      Precio: ${this.Precio.toFixed(2)} €,
      Estado: ${this.Estado}
    }`;
    }
}