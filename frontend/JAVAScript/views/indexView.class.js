export default class View {
    constructor() {
        this.contenedordatosCategorias = document.getElementById("datosCategoria");
        this.contenedorCardsCategoria = document.getElementById("contenedorcardsCategoria");
        this.contenedorServicios = document.getElementById("contenedorCategoria");

        this.btnLeft = document.getElementById("btn-left");
        this.btnRight = document.getElementById("btn-right");
        this.dropdown = document.getElementById('user-dropdown');
        this.profileIcon = document.querySelector('.icono-perfil');
        this.rightHeader = document.querySelector('.right-header');
    }

    // Renderiza una categoría individual
    renderNewServicioPorCategoria(prod, numeroServicios) {
        const textoServicios = numeroServicios > 0
            ? `${numeroServicios} servicios ya disponibles`
            : "Se activarán pronto";

        const textoLectura = `Categoría: ${prod.Nombre}. ${textoServicios}.`;

        const html = `
      <div class="course-categoria" data-id="${prod.IDCategoria}" tabindex="0">
        <button class="btn-narrar" aria-label="Escuchar" data-texto="${textoLectura}">🔊</button>
        <div class="course">
          <img src="./IMG/image${prod.IDCategoria}.jpg" alt="${prod.Nombre}" />
          <h3>${prod.Nombre}</h3>
          <p>${textoServicios}</p>
        </div>
      </div>
    `;

        this.contenedordatosCategorias.insertAdjacentHTML("beforeend", html);
    }

    // Renderiza servicios dentro de un carousel
    renderNewServicio(prod, categorias, usuarios) {
        const categoria = categorias.find(cat => cat.IDCategoria === prod.IDCategoria);
        if (!categoria) return;

        const imagen = prod.IDImagen === "" ? `image${prod.IDCategoria}.jpg` : prod.IDImagen;

        // Revisamos si ya existe un carousel
        let carousel = this.contenedorServicios.querySelector(`#carousel-${categoria.IDCategoria}`);
        if (!carousel) {
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

            // Añadir scroll dinámico
            const track = carousel.querySelector(".carousel-track");
            const btnLeft = carousel.querySelector(".carousel-btn.left");
            const btnRight = carousel.querySelector(".carousel-btn.right");

            btnRight.addEventListener("click", () => {
                const card = track.querySelector(".course-completo");
                if (!card) return;
                const cardWidth = card.offsetWidth + 10; // ancho real + gap
                track.scrollBy({ left: cardWidth, behavior: "smooth" });
            });

            btnLeft.addEventListener("click", () => {
                const card = track.querySelector(".course-completo");
                if (!card) return;
                const cardWidth = card.offsetWidth + 10;
                track.scrollBy({ left: -cardWidth, behavior: "smooth" });
            });
        }

        const usuario = usuarios.find(u => u.IDUsuario === prod.IDUsuarioCreacion) || {};
        const categoryClass = `category-${categoria.Nombre.toLowerCase().replace(/\s+/g, '-')}`;

        const textoLectura = `Servicio de ${prod.Nombre}. Descripción: ${prod.Descripcion}. Precio: ${prod.Precio} euros.`;

        const html = `
      <div class="course-completo ${categoryClass}">
        <button class="btn-narrar" aria-label="Escuchar" data-texto="${textoLectura}">🔊</button>
        <div class="course" data-id="${prod.IDServicio}">
          <img src="./IMG/${imagen}" alt="${prod.Nombre}" />
          <h3>${prod.Nombre}</h3>
          <p>${prod.Descripcion}</p>
          <div class="course-footer">
            <span class="price">Precio - ${prod.Precio}€ por persona · ${usuario.Valoracion || '0,0'} /5</span>
          </div>
        </div>
        <div class="course-trasera">
          <h4>Información del Usuario</h4>
          <p>Nombre: ${usuario.Nombre || 'N/A'} ${usuario.Apellidos || ''}</p>
          <p>Teléfono: ${usuario.Telefono || 'N/A'}</p>
          <p>Email: ${usuario.Correo || 'N/A'}</p>
          <button class="btn-trasera">Consultar Información</button>
          <button class="btn-trasera-Comprar">Comprar</button>
        </div>
      </div>
    `;

        carousel.querySelector(".carousel-track").insertAdjacentHTML("beforeend", html);

        // Evento comprar
        carousel.querySelector(".carousel-track").querySelectorAll(".btn-trasera-Comprar")
            .forEach(btn => {
                btn.addEventListener("click", (e) => {
                    const card = e.target.closest(".course-completo");
                    if (!card) return;
                    const idProducto = card.querySelector(".course").dataset.id;
                    window.open(`../backend/auth/producto.php?id=${idProducto}`, "_blank");
                });
            });
    }

    renederServiciosPorCategoria(prod, categorias, usuarios) {
        this.contenedorServicios.innerHTML = "";
        const categoria = categorias.find(cat => cat.IDCategoria === prod.IDCategoria);
        if (!categoria) return;

        const usuario = usuarios.find(u => u.IDUsuario === prod.IDUsuarioCreacion) || {};
        const categoryClass = `category-${categoria.Nombre.toLowerCase().replace(/\s+/g, '-')}`;

        const imagen = !prod.IDImagen || prod.IDImagen === "" ? `image${prod.IDCategoria}.jpg` : prod.IDImagen;

        const textoLectura = `Servicio de ${prod.Nombre}. ${prod.Descripcion}.`;

        const html = `
      <div class="course-completo ${categoryClass}">
        <button class="btn-narrar" aria-label="Escuchar" data-texto="${textoLectura}">🔊</button>
        <div class="course" data-id="${prod.IDServicio}">
          <img src="./IMG/${imagen}" alt="${prod.Nombre}" />
          <h3>${prod.Nombre}</h3>
          <p>${prod.Descripcion}</p>
          <div class="course-footer">
            <span class="price">Precio - ${prod.Precio}€ por persona · ${usuario.Valoracion || '0,0'} /5</span>
          </div>
        </div>
        <div class="course-trasera">
          <h4>Información del Usuario</h4>
          <p>Nombre: ${usuario.Nombre || 'N/A'} ${usuario.Apellidos || ''}</p>
          <p>Teléfono: ${usuario.Telefono || 'N/A'}</p>
          <p>Email: ${usuario.Correo || 'N/A'}</p>
          <button class="btn-trasera">Contactar</button>
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

    renderFilterCategorias(callback) {
        if (!this.contenedordatosCategorias) return;
        this.contenedordatosCategorias.addEventListener("click", (e) => {

            if (e.target.closest(".btn-narrar")) return; // EVITAR PROPAGACIÓN DE EVENTOS

            const btnCategorias = e.target.closest(".course-categoria");
            if (!btnCategorias) return;
            const id = btnCategorias.dataset.id;
            if (callback) callback(id);
        });
    }

    renderToggleDropdown() {
        if (!this.dropdown) return;
        const isActive = this.dropdown.classList.contains("active");
        document.querySelectorAll(".user-dropdown").forEach(el => el.classList.remove("active"));
        if (!isActive) {
            this.dropdown.classList.add("active");
            document.addEventListener("click", this.renderCloseDropdownOnClickOutside);
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
            document.removeEventListener("click", this.renderCloseDropdownOnClickOutside);
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
                event.preventDefault();
                handler();
            }
        });
    }

    mostrarErrores(message) {
        alert("Error :" + message);
    }
}
