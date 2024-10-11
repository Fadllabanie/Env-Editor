<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit .env</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <style>
        body {
            background-color: #f8f9fa;
        }

        .form-floating {
            position: relative;
        }

        .fa-trash {
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .fa-trash:hover {
            color: #dc3545;
        }

        .alert-success {
            position: absolute;
            top: -50px;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            text-align: center;
            z-index: 10;
        }

        .btn-secondary {
            background-color: #6c757d;
        }
    </style>
</head>

<body>
    <div class="container mt-5" x-data="envEditor()">
        <div class="card shadow-lg border-0">
            <div class="card-header text-center bg-primary text-white">
                <h3>Edit .env File</h3>
            </div>

            <form method="POST" action="{{ route('env.logout') }}" class="my-2 mx-4">
                @csrf
                <div class="text-end">
                    <button type="submit" class="btn btn-outline-danger">Logout <i class="fas fa-sign-out-alt"></i></button>
                </div>
            </form>

            <div class="card-body position-relative">
                <!-- Alert -->
                <div x-show="showAlert" class="alert alert-success alert-dismissible fade show" role="alert">
                    .env file updated successfully!
                    <button type="button" class="btn-close" @click="showAlert = false" aria-label="Close"></button>
                </div>

                <!-- Form -->
                <form action="{{ route('env.update') }}" method="POST">
                    @csrf

                    <div class="row g-3">
                        @foreach ($envArray as $key => $value)
                            <div class="col-md-6">
                                <div class="form-floating mb-3 d-flex align-items-center">
                                    <input type="text" name="env[{{ $key }}]" class="form-control"
                                        id="floating{{ $key }}" value="{{ $value }}" placeholder="{{ $key }}">
                                    <label for="floating{{ $key }}">{{ $key }}</label>
                                    <i class="fas fa-trash text-danger ms-2" @click="confirmDelete('{{ $key }}')"></i>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Add new key-value pairs -->
                    <h4 class="mt-4">Add New Key-Value Pairs</h4>
                    <template x-for="(input, index) in inputs" :key="index">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-5">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" x-model="input.key" placeholder="Enter Key">
                                    <label>Key</label>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" x-model="input.value" placeholder="Enter Value">
                                    <label>Value</label>
                                </div>
                            </div>
                            <div class="col-md-2 text-center">
                                <i class="fas fa-trash text-danger" @click="removeInput(index)"></i>
                            </div>
                        </div>
                    </template>

                    <div class="text-center mb-3">
                        <button type="button" class="btn btn-secondary" @click="addInput()">Add New Key-Value <i class="fas fa-plus-circle"></i></button>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Update .env</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

    <!-- Alpine.js Logic -->
    <script>
        function envEditor() {
            return {
                showAlert: false,
                inputs: [],
                addInput() {
                    this.inputs.push({ key: '', value: '' });
                },
                removeInput(index) {
                    this.inputs.splice(index, 1);
                },
                confirmDelete(key) {
                    if (confirm('Are you sure you want to delete this key?')) {
                        this.removeExistingInput(key);
                    }
                },
                removeExistingInput(key) {
                    document.querySelector(`[name="env[${key}]"]`).closest('.col-md-6').remove();
                },
                submitForm() {
                    const form = document.querySelector('form');
                    this.inputs.forEach(input => {
                        if (input.key && input.value) {
                            let inputKey = document.createElement('input');
                            inputKey.setAttribute('type', 'hidden');
                            inputKey.setAttribute('name', `env[${input.key}]`);
                            inputKey.setAttribute('value', input.value);
                            form.appendChild(inputKey);
                        }
                    });
                    form.submit();
                    this.showAlert = true;
                }
            }
        }
    </script>
</body>

</html>
