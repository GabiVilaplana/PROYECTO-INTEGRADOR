export default class View {
    constructor() {
        this.contenedorCursos = document.querySelector(".courses.carousel-track");
        // const track = document.getElementById("carousel-courses");
        this.btnLeft = document.getElementById("btn-left");
        this.btnRight = document.getElementById("btn-right");
    }

    renderNewServicio(prod, categorias, usuarios) {
        const categoria = categorias.find(cat => cat.IDCategoria === prod.IDCategoria);
        const categoryClass = categoria
            ? `category-${categoria.Nombre.toLowerCase().replace(/\s+/g, '-')}`
            : 'category-otro';


        const usuario = usuarios.find(u => u.IDUsuario === prod.IDUsuarioCreacion) || {};



        const html = `
        <div class="course-completo ${categoryClass}">
            <div class="course">
                <img src="./IMG/image${prod.IDCategoria}.jpg" alt="${prod.Nombre}" />
                <h3>${prod.Nombre}</h3>
                <p>${prod.Descripcion}</p>
                <div class="course-footer">
                    <span class="price">Precio - ${prod.Precio}€ por persona · <img src ="./SVG/estrellaGris.svg" alt="estrella" class="icon-star"> ${usuario.Valoracion || '0,0'}</span>
                </div>
            </div>
            <div class="course-trasera">
                <h4>Información del Usuario</h4>
                <p>Nombre: ${usuario.Nombre || 'Desconocido'}</p>
                <p>Teléfono: ${usuario.Telefono || 'N/A'}</p>
                <p>Email: ${usuario.Correo || 'N/A'}</p>
                <div class="mapa-container">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d12454.997924615587!2d-0.48873568354075925!3d38.70059844253098!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd61864e204bb377%3A0x3270bc5ab4510472!2sAlcoy%2C%20Alicante!5e0!3m2!1ses!2ses!4v1763423541393!5m2!1ses!2ses"
                    allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
          <button class="btn-trasera">Contactar</button>
        </div>
    </div>`;
        const div = document.createElement("div");
        div.innerHTML = html;
        this.contenedorCursos.appendChild(div);

    }

    sliceCards() {
        if (!this.btnLeft || !this.btnRight) {
            console.warn("Botones o track no encontrados en el DOM");
            return;
        }
        this.btnRight.addEventListener("click", () => {
            this.contenedorCursos.scrollBy({ left: 300, behavior: "smooth" });
        });

        this.btnLeft.addEventListener("click", () => {
            this.contenedorCursos.scrollBy({ left: -300, behavior: "smooth" });
        });
    }


    mostrarErrores(message) {

        alert("Error :" + message);
        /*const newMessageDiv = document.createElement('div');
        newMessageDiv.className = "col-sm-12 alert alert-danger alert-dismissible fade show"
        newMessageDiv.innerHTML = `
        <span><strong>ATENCIÓN: </strong>${message}</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" onclick="this.parentElement.remove()"></button>`

        document.getElementById('messages').appendChild(newMessageDiv);*/
    }


}