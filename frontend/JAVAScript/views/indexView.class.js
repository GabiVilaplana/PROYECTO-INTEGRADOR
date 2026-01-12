export default class View {
    constructor() {
        //this.contenedorCategorias = document.querySelector(".courses.carousel-categorias")

        this.contenedordatosCategorias = document.getElementById("datosCategoria");
        this.contenedorCardsCategoria = document.getElementById("contenedorcardsCategoria");
        this.contenedorServicios = document.getElementById("contenedorCategoria");
        const firstCard = this.contenedordatosCategorias.querySelector('.course-categoria');
        this.cardWidth = firstCard ? firstCard.offsetWidth : 300; // fallback


        this.btnLeft = document.getElementById("btn-left");
        this.btnRight = document.getElementById("btn-right");
        this.dropdown = document.getElementById('user-dropdown');
        this.profileIcon = document.querySelector('.icono-perfil');
        this.rightHeader = document.querySelector('.right-header')
    }


    renderNewServicioPorCategoria(prod, numeroServicios) {
        // Crear la card
        const textoServicios =
            numeroServicios > 0
                ? `${numeroServicios} servicios ya disponibles`
                : "Se activarán pronto";


        const html = `
        <div class="course-categoria" data-id="${prod.IDCategoria}"  tabindex="0">
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

        const imagen = prod.IDImagen === ""
            ? `image${prod.IDCategoria}.jpg`
            : prod.IDImagen;
        // Revisamos si ya existe un carousel para esta categoría
        let carousel = this.contenedorServicios.querySelector(`#carousel-${categoria.IDCategoria}`);
        if (!carousel) {
            // Crear el wrapper del carousel
            carousel = document.createElement("div");
            carousel.className = "carousel-wrapper";
            carousel.id = `carousel-${categoria.IDCategoria}`;
            carousel.innerHTML = `
            <h2>${categoria.Nombre}</h2>
            <button class="carousel-btn left"><</button>
            <button class="carousel-btn right">></button>
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
        <div class="course-completo ${categoryClass}" >
            <div class="course" data-id="${prod.IDServicio}">
                <img src="./IMG/${imagen}" alt="${prod.Nombre}" />
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

                <button class="btn-trasera">Contactar</button>
                <button class="btn-trasera-Comprar">Comprar</button>

            </div>
        </div>
    `;

        // Insertar la card dentro del track del carousel de esta categoría
        carousel.querySelector(".carousel-track").insertAdjacentHTML("beforeend", html);
    }


    renederServiciosPorCategoria(prod, categorias, usuarios) {
        this.contenedorServicios.innerHTML = "";
        const categoria = categorias.find(cat => cat.IDCategoria === prod.IDCategoria);
        if (!categoria) return;
        // Crear la card
        const usuario = usuarios.find(u => u.IDUsuario === prod.IDUsuarioCreacion) || {};
        const categoryClass = `category-${categoria.Nombre.toLowerCase().replace(/\s+/g, '-')}`;

        const imagen = !prod.IDImagen || prod.IDImagen === ""
            ? `image${prod.IDCategoria}.jpg`
            : prod.IDImagen;


        const html = `
        <div class="course-completo ${categoryClass}" >
            <div class="course" data-id="${prod.IDServicio}">
                <img src="./IMG/${imagen}" alt="${prod.Nombre}" />
                <h3>${prod.Nombre}</h3>
                <p>${prod.Descripcion}</p>
                <div class="course-footer">
                    <span class="price">Precio - ${prod.Precio}€ por persona · 
                        ${usuario.Valoracion || '0,0'} /5
                    </span>
                </div>
            </div>
        </div>
    `;

        let div = this.contenedorCardsCategoria.querySelector(`[data-id="${prod.id}"]`);
        if (div) {
            div.innerHTML = html;
        } else {
            const div = document.createElement("div");
            div.dataset.id = prod.IDServicio;
            div.className = "card";

            div.innerHTML = html;
            this.contenedorCardsCategoria.appendChild(div);
        }

    }

    /*renderSeleccionarServicio(callback) {
        if (!this.contenedorCardsCategoria || !this.contenedorServicios) {
            this.
            const btnElegir  = e.target.closest(".card");
            if (!btnElegir) return;
            const card = btnElegir.dataset.id;
            console.log(card);
            if (!card) this.mostrarErrores("Errores al obtener la ID del coche, está vacia");
            //callback(card);
        }

    }*/






    renderFilterCategorias(callback) {
        if (!this.contenedordatosCategorias) return;

        this.contenedordatosCategorias.addEventListener("click", (e) => {
            console.log(e);
            const btnCategorias = e.target.closest(".course-categoria");
            if (!btnCategorias) return;


            const id = btnCategorias.dataset.id;
            console.log("BOTÓN DE CATEGORÍAS:", id);

            if (callback) callback(id);
        });
    }


    sliceCards() {
        if (!this.btnLeft || !this.btnRight) {
            console.warn("Botones o track no encontrados en el DOM");
            return;
        }
        this.btnRight.addEventListener("click", () => {
            this.contenedordatosCategorias.scrollBy({ left: this.cardWidth, behavior: "smooth" });
        });

        this.btnLeft.addEventListener("click", () => {
            this.contenedordatosCategorias.scrollBy({ left: -this.cardWidth, behavior: "smooth" });
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

        this.rightHeader.addEventListener("keydown", (event) => {
        if (event.key === " " || event.key === "Enter") {
            event.preventDefault(); // evita scroll en el espacio
            handler();
        }
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