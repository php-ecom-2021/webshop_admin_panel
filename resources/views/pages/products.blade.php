@extends('layouts.app')

@section('content')
    @include('layouts.headers.customCards')
    <script>
        function updateCategoryName(categoryName) {
            document.getElementById('dropdownMenuButton').innerHTML = categoryName;
        }

        function searchForProduct() {
            let inputSearch = document.getElementById('search');

            fetch('products/search/' + inputSearch.value)
                .then(response => response.json())
                .then(data => {
                    let str = '';
                    data.forEach(product => {
                        str +=
                            `
                            <tr>
                                    <th scope="row">
                                        <div class="media align-items-center">
                                            <div class="media-body">
                                                <span class="name mb-0 text-sm">${product.name}</span>
                                            </div>
                                        </div>
                                    </th>
                                    <td class="budget" id="categoryName">
                                    ${product.categoryName}
                            </td>
                            <td class="budget">
                            ${product.description.substring(0, 20)} ...
                                    </td>
                                    <td>
                                        ${product.price} DKK
                                    </td>
                                    <td>
                                        ${product.inStock}
                            </td>
                            <td class="text-right">
                                <div class="dropdown">
                                    <a class="btn btn-sm btn-icon-only text-light" href="#" role="button"
                                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                        <a class="dropdown-item" data-toggle="modal"
                                           data-target="#modal${product.productID}">Rediger</a>
                                                <a class="dropdown-item" href="products/delete/${product.productID}">Slet</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            `
                    });
                    document.getElementById('tbody').innerHTML = str;

                });
        }

        document.addEventListener('DOMContentLoaded', e => {
            let inputSearch = document.getElementById('search');

            inputSearch.addEventListener('keyup', e => {
                e.preventDefault();
                searchForProduct();
            });
        });
    </script>
    <div class="container-fluid mt--7">
        <div class="text-left mb-3">
            <a class="btn btn-secondary" data-toggle="modal"
               data-target="#createProductModal">Opret nyt produkt
            </a>
        </div>
        @if(session('message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <span class="alert-icon"><i class="ni ni-like-2"></i></span>
                <span class="alert-text"><strong>Opdateret!</strong> {{session('message')}}</span>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="row">
            <div class="col">
                <div class="card">
                    <!-- Card header -->
                    <div class="card-header border-0">
                        <h3 class="mb-0">Produkter</h3>
                    </div>
                    <input type="text" class="form-control w-25 mt-3 mb-3 ml-3" placeholder="S??g efter produkt"
                           name="search" id="search">
                    <!-- Light table -->
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                            <tr>
                                <th scope="col" class="sort" data-sort="name">Produkt navn</th>
                                <th scope="col" class="sort" data-sort="budget">Kategori</th>
                                <th scope="col" class="sort" data-sort="status">Beskrivelse</th>
                                <th scope="col" class="sort" data-sort="status">Pris</th>
                                <th scope="col" class="sort" data-sort="status">Lagerstatus</th>
                                <th scope="col"></th>
                            </tr>
                            </thead>
                            <tbody class="list" id="tbody">
                            @foreach($products as $product)
                                <tr>
                                    <th scope="row">
                                        <div class="media align-items-center">
                                            <div class="media-body">
                                                <span class="name mb-0 text-sm">{{$product->name}}</span>
                                            </div>
                                        </div>
                                    </th>
                                    <td class="budget">
                                        {{$product->categoryName}}
                                    </td>
                                    <td class="budget">
                                        {{substr($product->description, 0, 20)}} ...
                                    </td>
                                    <td>
                                        {{$product->price}} DKK
                                    </td>
                                    <td>
                                        {{$product->inStock}}
                                    </td>
                                    <td class="text-right">
                                        <div class="dropdown">
                                            <a class="btn btn-sm btn-icon-only text-light" href="#" role="button"
                                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                <a class="dropdown-item" data-toggle="modal"
                                                   data-target="#modal{{$product->productID}}">Rediger</a>
                                                <a class="dropdown-item" href="products/delete/{{$product->productID}}">Slet</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <div class="modal fade" id="modal{{$product->productID}}" tabindex="-1" role="dialog"
                                     aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">{{$product->name}}</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form role="form" action="{{url('/products/update')}}" method="post">
                                                    @csrf()
                                                    <div class="form-group mb-3">
                                                        <input type="hidden" id="productID" name="productID"
                                                               value="{{$product->productID}}">
                                                        <div class="mb-2 ml-1">
                                                            <span>Produkt navn</span>
                                                        </div>
                                                        <div
                                                            class="input-group input-group-merge input-group-alternative">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"></span>
                                                            </div>
                                                            <input class="form-control" placeholder="{{$product->name}}"
                                                                   type="text" name="name">
                                                        </div>
                                                    </div>
                                                    <div class="form-group mb-3">
                                                        <div class="mb-2 ml-1">
                                                            <span>Kategori</span>
                                                        </div>
                                                        <select class="form-control"
                                                                name="category">
                                                            @foreach($categories as $category)
                                                                <option
                                                                    onclick="updateCategoryName('{{$category->categoryName}}')">{{$category->categoryName}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="form-group mb-3">
                                                        <div class="mb-2 ml-1">
                                                            <span>Beskrivelse</span>
                                                        </div>
                                                        <div
                                                            class="input-group input-group-merge input-group-alternative">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"></span>
                                                            </div>
                                                            <textarea class="form-control"
                                                                      id="exampleFormControlTextarea1" rows="3"
                                                                      placeholder="{{$product->description}}"
                                                                      name="description"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="form-group mb-3">
                                                        <div class="mb-2 ml-1">
                                                            <span>Pris</span>
                                                        </div>
                                                        <div
                                                            class="input-group input-group-merge input-group-alternative">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"></span>
                                                            </div>
                                                            <input class="form-control"
                                                                   placeholder="{{$product->price}}"
                                                                   type="number" name="price">
                                                        </div>
                                                    </div>
                                                    <div class="form-group mb-3">
                                                        <div class="mb-2 ml-1">
                                                            <span>Lagerstatus</span>
                                                        </div>
                                                        <div
                                                            class="input-group input-group-merge input-group-alternative">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"></span>
                                                            </div>
                                                            <input class="form-control"
                                                                   placeholder="{{$product->inStock}}"
                                                                   type="number" name="inStock">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">
                                                            Luk
                                                        </button>
                                                        <button type="submit" class="btn btn-primary">Gem</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            <div class="modal fade" id="createProductModal" tabindex="-1" role="dialog"
                                 aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Opret nyt produkt</h5>
                                            <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form role="form" action="{{url('/products/create')}}" method="post"
                                                  enctype="multipart/form-data">
                                                @csrf()
                                                <div class="form-group mb-3">
                                                    <div class="mb-2 ml-1">
                                                        <span>Produkt navn</span>
                                                    </div>
                                                    <div
                                                        class="input-group input-group-merge input-group-alternative">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"></span>
                                                        </div>
                                                        <input class="form-control" placeholder=""
                                                               type="text" name="name">
                                                    </div>
                                                </div>
                                                <div class="form-group mb-3">
                                                    <div class="mb-2 ml-1">
                                                        <span>Varenummer</span>
                                                    </div>
                                                    <div
                                                        class="input-group input-group-merge input-group-alternative">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"></span>
                                                        </div>
                                                        <input class="form-control" placeholder=""
                                                               type="number" name="partNumber">
                                                    </div>
                                                </div>
                                                <div class="form-group mb-3">
                                                    <div class="mb-2 ml-1">
                                                        <span>Kategori</span>
                                                    </div>
                                                    <select class="form-control"
                                                            name="category">
                                                        @foreach($categories as $category)
                                                            <option
                                                                onclick="updateCategoryName('{{$category->categoryName}}')">{{$category->categoryName}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group mb-3">
                                                    <div class="mb-2 ml-1">
                                                        <span>Beskrivelse</span>
                                                    </div>
                                                    <div
                                                        class="input-group input-group-merge input-group-alternative">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"></span>
                                                        </div>
                                                        <textarea class="form-control"
                                                                  id="exampleFormControlTextarea1" rows="3"
                                                                  placeholder=""
                                                                  name="description"></textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group mb-3">
                                                    <div class="mb-2 ml-1">
                                                        <span>Produkt billede</span>
                                                    </div>
                                                    <input type="file" id="img" name="img" accept="image/*">
                                                </div>
                                                <div class="form-group mb-3">
                                                    <div class="mb-2 ml-1">
                                                        <span>Pris</span>
                                                    </div>
                                                    <div
                                                        class="input-group input-group-merge input-group-alternative">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"></span>
                                                        </div>
                                                        <input class="form-control"
                                                               placeholder=""
                                                               type="number" name="price">
                                                    </div>
                                                </div>
                                                <div class="form-group mb-3">
                                                    <div class="mb-2 ml-1">
                                                        <span>Lagerstatus</span>
                                                    </div>
                                                    <div
                                                        class="input-group input-group-merge input-group-alternative">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"></span>
                                                        </div>
                                                        <input class="form-control"
                                                               placeholder=""
                                                               type="number" name="inStock">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">
                                                        Luk
                                                    </button>
                                                    <button type="submit" class="btn btn-primary">Gem</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.footers.auth')
    </div>
@endsection

@push('js')
    <script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.extension.js"></script>
@endpush
