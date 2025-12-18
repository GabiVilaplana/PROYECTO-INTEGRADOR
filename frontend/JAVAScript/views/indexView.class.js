export default class View {
    constructor() {
        //this.contenedorCategorias = document.querySelector(".courses.carousel-categorias");

        this.contenedordatosCategorias = document.getElementById("datosCategoria");
        this.contenedorServicios = document.getElementById("contenedorCategoria");

        this.btnLeft = document.getElementById("btn-left");
        this.btnRight = document.getElementById("btn-right");
        this.dropdown = document.getElementById('user-dropdown');
        this.profileIcon = document.querySelector('.icono-perfil');
    }


    renderNewServicioPorCategoria(prod, numeroServicios) {
        // Crear la card
        const textoServicios =
            numeroServicios > 0
                ? `${numeroServicios} servicios ya disponibles`
                : "Se activarán pronto";


        const html = `
        <div class="course-categoria">
            <div class="course">
                <img src="./IMG/image${prod.IDCategoria}.jpg" alt="${prod.Nombre}" />
                <h3>${prod.Nombre}</h3>
                <p>${textoServicios} </p>
            </div>
        </div>
    `;

        // Insertar la card debajo del h2
        this.contenedordatosCategorias.insertAdjacentHTML("beforeend", html);
    }


    renderNewServicio(prod, categorias, usuarios) {
        const categoria = categorias.find(cat => cat.IDCategoria === prod.IDCategoria);
        if (!categoria) return;

        // Revisamos si ya existe un carousel para esta categoría
        let carousel = this.contenedorServicios.querySelector(`#carousel-${categoria.IDCategoria}`);
        if (!carousel) {
            // Crear el wrapper del carousel
            carousel = document.createElement("div");
            carousel.className = "carousel-wrapper";
            carousel.id = `carousel-${categoria.IDCategoria}`;
            carousel.innerHTML = `
            <h2>${categoria.Nombre}</h2>
            <button class="carousel-btn left">‹</button>
            <button class="carousel-btn right">›</button>
            <section class="courses carousel-track"></section>
        `;
            this.contenedorServicios.appendChild(carousel);

            // Añadir funcionalidad a los botones de scroll de este carousel
            const track = carousel.querySelector(".carousel-track");
            const btnLeft = carousel.querySelector(".carousel-btn.left");
            const btnRight = carousel.querySelector(".carousel-btn.right");

            btnRight.addEventListener("click", () => {
                track.scrollBy({ left: 300, behavior: "smooth" });
            });
            btnLeft.addEventListener("click", () => {
                track.scrollBy({ left: -300, behavior: "smooth" });
            });
        }

        // Crear la card
        const usuario = usuarios.find(u => u.IDUsuario === prod.IDUsuarioCreacion) || {};
        const categoryClass = `category-${categoria.Nombre.toLowerCase().replace(/\s+/g, '-')}`;

        const html = `
        <div class="course-completo ${categoryClass}">
            <div class="course">
                <img src="./IMG/image${prod.IDCategoria}.jpg" alt="${prod.Nombre}" />
                <h3>${prod.Nombre}</h3>
                <p>${prod.Descripcion}</p>
                <div class="course-footer">
                    <span class="price">Precio - ${prod.Precio}€ por persona · 
                        ${usuario.Valoracion || '0,0'} /5
                    </span>
                </div>
            </div>
            <div class="course-trasera">
                <h4>Información del Usuario</h4>
                <p>Nombre: ${usuario.Nombre}  ${usuario.Apellidos}</p>
                <p>Teléfono: ${usuario.Telefono || 'N/A'}</p>
                <p>Email: ${usuario.Correo || 'N/A'}</p>
                <div class="mapa-container">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d12454.997924615587!2d-0.48873568354075925!3d38.70059844253098!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd61864e204bb377%3A0x3270bc5ab4510472!2sAlcoy%2C%20Alicante!5e0!3m2!1ses!2ses!4v1763423541393!5m2!1ses!2ses" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
                <button class="btn-trasera">Contactar</button>
            </div>
        </div>
    `;

        // Insertar la card dentro del track del carousel de esta categoría
        carousel.querySelector(".carousel-track").insertAdjacentHTML("beforeend", html);
    }


    sliceCards() {
        if (!this.btnLeft || !this.btnRight) {
            console.warn("Botones o track no encontrados en el DOM");
            return;
        }
        this.btnRight.addEventListener("click", () => {
            this.contenedordatosCategorias.scrollBy({ left: 300, behavior: "smooth" });
        });

        this.btnLeft.addEventListener("click", () => {
            this.contenedordatosCategorias.scrollBy({ left: -300, behavior: "smooth" });
        });
    }


    renderToggleDropdown() {
        if (!this.dropdown) return;

        const isActive = this.dropdown.classList.contains("active");

        document.querySelectorAll(".user-dropdown").forEach(el =>
            el.classList.remove("active")
        );

        if (!isActive) {
            this.dropdown.classList.add("active");
            document.addEventListener(
                "click",
                this.renderCloseDropdownOnClickOutside
            );
        }
    }

    renderCloseDropdownOnClickOutside = (event) => {
        if (
            this.dropdown &&
            this.profileIcon &&
            !this.dropdown.contains(event.target) &&
            !this.profileIcon.contains(event.target)
        ) {
            this.dropdown.classList.remove("active");
            document.removeEventListener(
                "click",
                this.renderCloseDropdownOnClickOutside
            );
        }
    };
    bindProfileDropdown(handler) {
        if (!this.profileIcon) return;

        this.profileIcon.addEventListener("click", (event) => {
            event.stopPropagation();
            handler();
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