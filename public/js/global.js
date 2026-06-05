class MatIcon extends HTMLElement {
  static observedAttributes = ["inner-class"];

  /** @type {HTMLSpanElement} */
  innerElement;
  innerClass = "";
  icon = "";

  constructor() {
    super();
  }

  connectedCallback() {
    this.icon = this.textContent;
    this.textContent = "";
    this.classList.add("d-flex");
    this.loadMaterialSymbol();
  }

  /**
   * @param {string} name
   * @param {string} oldValue
   * @param {string} newValue
   */
  attributeChangedCallback(name, oldValue, newValue) {
    switch (name) {
      case "inner-class": {
        this.innerClass = newValue;
        if (this.innerElement) {
          this.innerElement.className = `material-symbols-outlined ${this.innerClass}`;
        }
        break;
      }
    }
  }

  loadMaterialSymbol() {
    const innerContainer = document.createElement("i");
    innerContainer.className = `material-symbols-outlined ${this.innerClass}`;
    innerContainer.textContent = this.icon;
    this.innerElement = innerContainer;
    this.appendChild(innerContainer);
  }
}
customElements.define("mat-icon", MatIcon);

function updateActiveNavbarLink() {
  /** @type {HTMLAnchorElement[]} */
  const NAVBAR_LINKS = Array.from(
    document.querySelectorAll(".nav-item .nav-link"),
  );
  NAVBAR_LINKS.forEach((link) => {
    if (link.href === location.href) {
      link.classList.add("active");
    }
  });
}

document.addEventListener("DOMContentLoaded", function () {
  updateActiveNavbarLink();
});
