document.addEventListener("DOMContentLoaded", () => {
  const state = {
    isMenuOpen: false,
    selectedCategory: "all",
    products: [
      {
        id: 1,
        name: "Silk Evening Gown",
        category: "dress",
        price: 180,
        size: "M",
        color: "red",
        available: true,
        image:
          "https://images.pexels.com/photos/30376507/pexels-photo-30376507.jpeg",
      },
      {
        id: 2,
        name: "Designer Scarf",
        category: "scarf",
        price: 45,
        color: "blue",
        available: true,
        image:
          "https://images.pexels.com/photos/4324953/pexels-photo-4324953.jpeg",
      },
      {
        id: 3,
        name: "Crystal Stilettos",
        category: "shoes",
        price: 120,
        size: "38",
        color: "silver",
        available: false,
        image:
          "https://images.pexels.com/photos/3602449/pexels-photo-3602449.jpeg",
      },
    ],
  };

  // DOM Elements
  const menuToggle = document.getElementById("menuToggle");
  const menu = document.getElementById("menu");
  const categoryFilters = document.getElementById("categoryFilters");
  const productGrid = document.getElementById("productGrid");

  // Initialize the UI
  initializeUI();

  // Event Listeners
  menuToggle.addEventListener("click", toggleMenu);

  function initializeUI() {
    renderProducts();

  }

  function toggleMenu() {
    state.isMenuOpen = !state.isMenuOpen;

    if (state.isMenuOpen) {
      menu.classList.remove("max-sm:hidden");
      menu.classList.add(
        "max-sm:flex",
        "max-sm:flex-col",
        "max-sm:absolute",
        "max-sm:top-[70px]",
        "max-sm:left-0",
        "max-sm:right-0",
        "max-sm:bg-white",
        "max-sm:p-5",
        "max-sm:shadow-md",
        "max-sm:z-10",
      );
    } else {
      menu.classList.add("max-sm:hidden");
      menu.classList.remove(
        "max-sm:flex",
        "max-sm:flex-col",
        "max-sm:absolute",
        "max-sm:top-[70px]",
        "max-sm:left-0",
        "max-sm:right-0",
        "max-sm:bg-white",
        "max-sm:p-5",
        "max-sm:shadow-md",
        "max-sm:z-10",
      );
    }
  }

function renderProducts() {
  const productGrid = document.getElementById("productGrid");
  productGrid.innerHTML = "";

  const filteredProducts = state.products;

  filteredProducts.forEach((product) => {
    const productCard = document.createElement("article");
    productCard.className =
      "product-card bg-white rounded-2xl shadow-md overflow-hidden transition-transform duration-300 ease-in-out";

    productCard.innerHTML = `
      <img
        src="${product.image}"
        alt="${product.name}"
        class="object-cover w-full h-[350px]"
      />
      <div class="p-5 space-y-4 text-center">
        <h3 class="text-lg font-medium text-zinc-800">${product.name}</h3>
        <div class="flex justify-between items-center">
          <span class="px-3 py-1 text-sm rounded-full ${
            product.available
              ? "bg-[#e8f5e9] text-[#2e7d32]"
              : "bg-[#ffebee] text-[#c62828]"
          }">
            ${product.available ? "Available" : "Booked"}
          </span>
          <button class="px-6 py-2 rounded-full bg-stone-500 text-white hover:bg-[#D4AF37] transition-colors">
            Rent Now
          </button>
        </div>
      </div>
    `;

    productGrid.appendChild(productCard);
  });
}
    productGrid.appendChild(productCard);
  });
}


      productGrid.appendChild(productCard);
    });
  }
});
