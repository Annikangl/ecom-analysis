<style>
    .thumbnail img {
        -webkit-filter: grayscale(0);
        filter: none;
        border-radius: 5px;
        background-color: #fff;
        border: 1px solid #ddd;
        padding: 5px;
    }

    .thumbnail img:hover {
        -webkit-filter: grayscale(1);
        filter: grayscale(1);
    }

    .thumbnail {
        padding: 5px;
    }

    .thumbnail img {
        -webkit-filter: grayscale(0);
        filter: none;
        border-radius: 5px;
        background-color: #fff;
        border: 1px solid #ddd;
        padding: 5px;
    }

    .thumbnail img:hover {
        -webkit-filter: grayscale(1);
        filter: grayscale(1);
    }

    .thumbnail {
        padding: 5px;
    }

    .modal {
        display: none; /* Скрыть модальное окно по умолчанию */
        position: fixed;
        z-index: 1; /* Сидеть поверх */
        padding-top: 100px; /* Расположение модального окна */
        left: 0;
        top: 0;
        width: 100%; /* Полная ширина */
        height: 100%; /* Полная высота */
        overflow: auto; /* Включите прокрутку, если нужно */
        background-color: rgb(0, 0, 0); /* Цвет фона */
        background-color: rgba(0, 0, 0, 0.9); /* Черный с небольшой прозрачностью */
    }

    /* Модальные изображения - нужно анимировать открытие */
    .modal-content {
        margin: auto;
        display: block;
        width: 80%;
        max-width: 700px;
        animation-name: zoom;
        animation-duration: 0.6s;
    }

    @keyframes zoom {
        from {
            transform: scale(0)
        }
        to {
            transform: scale(1)
        }
    }

    /* Кнопка закрыть (x) */
    .close {
        position: absolute;
        top: 15px;
        right: 35px;
        color: #f1f1f1;
        font-size: 40px;
        font-weight: bold;
        transition: 0.3s;
    }

    .close:hover,
    .close:focus {
        color: #bbb;
        text-decoration: none;
        cursor: pointer;
    }

</style>

<div class="container">
    <h1 class="h3 text-center my-4">
        @if($images)
            Изображения
        @else
            Изображений пока нет
        @endif
    </h1>
    <div class="row">
        @foreach($images as $image)
            <div class="col-lg-3 col-md-4 col-6 thumbnail">
                <img class="img-fluid"
                     src="{{ $image['url'] }}"
                     alt="Image"
                     onclick="showImage(this.src)">
            </div>
        @endforeach
    </div>
</div>


<div id="imageModal" class="modal">
    <span class="close">&times;</span>
    <img class="modal-content" id="modal-img" alt="">
</div>


<script>
    const modal = document.getElementById('imageModal');

    function showImage(src) {
        const modalImg = document.getElementById("modal-img");
        document.getElementById("caption");
        modal.style.display = "block";
        modalImg.src = src;
    }

    const span = document.getElementsByClassName("close")[0];

    span.onclick = function () {
        modal.style.display = "none";
    }

    window.onclick = function (event) {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    }

</script>

