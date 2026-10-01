document.addEventListener("DOMContentLoaded", () => {
    const imageInput = document.getElementById("image");
    const imagePreview = document.getElementById("image-preview");
    const imagePlaceholder = document.getElementById("image-placeholder");
    const imageSelected = document.getElementById("image-selected");
    const imageName = document.getElementById("image-name");
    const removeImage = document.getElementById("remove-image");
    const removeImageInput = document.getElementById("remove-image-input");

    if (
        !imageInput ||
        !imagePreview ||
        !imagePlaceholder ||
        !imageSelected ||
        !imageName ||
        !removeImage ||
        !removeImageInput
    ) {
        return;
    }
    let previewUrl = null;

    imageInput.addEventListener("change", () => {
        const file = imageInput.files?.[0];

        if (!file) {
            return;
        }

        if (!file.type.startsWith("image/")) {
            imageInput.value = "";
            return;
        }

        if (file.size > 5 * 1024 * 1024) {
            imageInput.value = "";
            alert("A imagem deve ter no máximo 5 MB.");
            return;
        }

        if (previewUrl) {
            URL.revokeObjectURL(previewUrl);
        }

        previewUrl = URL.createObjectURL(file);

        imagePreview.src = previewUrl;
        imagePreview.classList.remove("hidden");

        imagePlaceholder.classList.add("hidden");

        imageSelected.classList.remove("hidden");
        imageName.textContent = file.name;

        removeImage.classList.remove("hidden");
        removeImage.classList.add("flex");

        removeImageInput.value = "0";
    });

    removeImage.addEventListener("click", (event) => {
        event.preventDefault();
        event.stopPropagation();

        imageInput.value = "";

        imagePreview.src = "";
        imagePreview.classList.add("hidden");

        imagePlaceholder.classList.remove("hidden");
        imageSelected.classList.add("hidden");

        imageName.textContent = "";

        removeImage.classList.add("hidden");
        removeImage.classList.remove("flex");

        removeImageInput.value = "1";

        if (previewUrl) {
            URL.revokeObjectURL(previewUrl);
            previewUrl = null;
        }
    });
});
