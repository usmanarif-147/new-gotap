<div>
    <style>
        .card-body {
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .mar {
            margin-left: 10%;
            margin-right: 10%;
        }

        p {
            font-size: 1rem;
            line-height: 1.5;
            text-align: center;
            max-width: 250px;
            /* Ensure the text doesn't stretch too wide */
            margin: 0 auto;
        }
    </style>
    <div class="container mt-5">
        <div class="card align-content-center" style="background-color: #ffffff;">
            <div class="card-body d-flex justify-content-center align-items-center" style="color: #000">
                <!-- Left Side -->
                <div class="d-flex flex-column align-items-center mar">
                    <img src="{{ asset('Accessory1.jpg') }}" width="300" alt="Feature 1" class="img-fluid shadow"
                        style="border-radius: 8px;">
                    <h5 class="mt-3">Get Gotaps Accessories</h5>
                    <p class="text-center" style="max-width: 250px;">Don't have any accessory? Purchase them here</p>
                    <button class="btn btn-dark mt-3">
                        <a href="https://gotaps.me/standard-products/" target="_blank" style="color: #ffffff">
                            Order Accessories</a>
                    </button>
                </div>

                <!-- Vertical Line -->
                <div class="d-flex" style="height: 400px;">
                    <div class="vr"></div>
                </div>

                <!-- Right Side -->
                <div class="d-flex flex-column align-items-center mar">
                    <img src="{{ asset('Accessory2.jpg') }}" width="300" alt="Feature 1" class="img-fluid shadow"
                        style="border-radius: 8px;">
                    <h5 class="mt-3">Activate Gotaps Accessories</h5>
                    <p class="text-center" style="max-width: 250px;">If you have all accessories you need, simply
                        activate them here</p>
                    <button class="btn btn-dark mt-3">Activate Accessories</button>
                </div>
            </div>
        </div>
    </div>
</div>
