// assets/app.js

/*
 * Welcome to your app's main JavaScript file!
 *
 * Ce fichier sera compilé en public/build/app.js
 */

// Début de l'importation des dépendances
import './styles/app.scss';

// Activez le mode debug pour Alpine.js en développement
document.addEventListener('DOMContentLoaded', () => {
    if (process.env.NODE_ENV === 'development') {
        window.Alpine = Alpine;
        Alpine.start();
    }
});

// Dark mode automatique (optionnel)
const setupDarkMode = () => {
    // Vérifie les préférences système ou le localStorage
    const isDarkMode = localStorage.theme === 'dark' ||
        (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches);

    if (isDarkMode) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
};

// Initialisation au chargement de la page
document.addEventListener('DOMContentLoaded', () => {
    setupDarkMode();

    // Notifications flash (pour Symfony)
    const flashMessages = document.querySelectorAll('.flash-message');
    flashMessages.forEach(message => {
        setTimeout(() => {
            message.classList.add('opacity-0', 'transition-opacity', 'duration-500');
            setTimeout(() => message.remove(), 500);
        }, 5000);
    });
});

// Gestion des formulaires avec Alpine.js
document.addEventListener('alpine:init', () => {
    Alpine.data('formState', () => ({
        loading: false,
        submitForm() {
            this.loading = true;
            // Vous pouvez ajouter ici une logique avant soumission
        }
    }));
});


// Gestion des collections LaTeX
document.addEventListener('DOMContentLoaded', function() {
    // Détection des changements pour le rendu LaTeX
    document.querySelectorAll('[data-latex-target="input"]').forEach(input => {
        const preview = input.closest('[data-latex-target="container"]').querySelector('[data-latex-target="preview"]');

        input.addEventListener('input', function() {
            preview.innerHTML = this.value;
            if (typeof MathJax !== 'undefined') {
                MathJax.typesetPromise([preview]);
            }
        });

        // Initial render
        preview.innerHTML = input.value;
        if (typeof MathJax !== 'undefined') {
            MathJax.typesetPromise([preview]);
        }
    });

    // Gestion des collections
    document.querySelectorAll('[data-collection-add]').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const collection = document.querySelector(this.dataset.collectionAdd);
            const index = collection.dataset.index || collection.children.length;
            const prototype = collection.dataset.prototype;

            const newItem = document.createElement('div');
            newItem.classList.add('collection-item', 'mb-4', 'p-4', 'bg-gray-700', 'rounded-lg', 'border', 'border-gray-600');
            newItem.innerHTML = prototype.replace(/__name__/g, index);

            collection.appendChild(newItem);
            collection.dataset.index = index + 1;

            // Initialiser le nouveau champ
            const newInput = newItem.querySelector('[data-latex-target="input"]');
            const newPreview = newItem.querySelector('[data-latex-target="preview"]');

            if (newInput && newPreview) {
                newInput.addEventListener('input', function() {
                    newPreview.innerHTML = this.value;
                    if (typeof MathJax !== 'undefined') {
                        MathJax.typesetPromise([newPreview]);
                    }
                });
            }
        });
    });

    // Suppression d'éléments de collection
    document.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('collection-remove')) {
            e.preventDefault();
            e.target.closest('.collection-item').remove();
        }
    });
});

function setupCollection(containerId, addButtonId) {
    const container = document.getElementById(containerId);
    if (!container) return;

    const addButton = document.getElementById(addButtonId);
    const prototype = container.dataset.prototype;

    // Ajouter un élément
    addButton.addEventListener('click', function() {
        const index = container.dataset.index;
        const newItem = document.createElement('div');
        newItem.classList.add('collection-item', 'mb-4', 'p-4', 'bg-gray-700', 'rounded-lg', 'border', 'border-gray-600');

        const newForm = prototype.replace(/__name__/g, index);
        newItem.innerHTML = `
            <div class="flex">
                <div class="flex-1">
                    ${newForm}
                    <div class="latex-preview prose prose-invert max-w-none p-3 bg-gray-900 rounded-lg mt-2"></div>
                </div>
                <button type="button" class="remove-item ml-2 text-red-500 hover:text-red-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </div>
        `;

        container.appendChild(newItem);
        container.dataset.index = parseInt(index) + 1;

        // Activer le rendu LaTeX
        setupLatexPreview(newItem);
    });

    // Initialiser les éléments existants
    container.querySelectorAll('.collection-item').forEach(item => {
        setupLatexPreview(item);
    });
}

// Gestion du rendu LaTeX
function setupLatexPreview(container) {
    const input = container.querySelector('.latex-input');
    const preview = container.querySelector('.latex-preview');

    if (!input || !preview) return;

    preview.innerHTML = input.value;
    if (typeof MathJax !== 'undefined') {
        MathJax.typesetPromise([preview]);
    }

    input.addEventListener('input', function() {
        preview.innerHTML = this.value;
        if (typeof MathJax !== 'undefined') {
            MathJax.typesetPromise([preview]);
        }
    });
}

// Initialisation au chargement
document.addEventListener('DOMContentLoaded', function() {
    // Configurer les collections
    setupCollection('details-container', 'add-detail');
    setupCollection('proofs-container', 'add-proof');
    setupCollection('hints-container', 'add-hint');
    setupCollection('solutions-container', 'add-solution');

    // Gestion générique de suppression
    document.addEventListener('click', function(e) {
        if (e.target && (e.target.classList.contains('remove-item') || e.target.closest('.remove-item'))) {
            e.preventDefault();
            const button = e.target.classList.contains('remove-item') ? e.target : e.target.closest('.remove-item');
            button.closest('.collection-item').remove();
        }
    });
});