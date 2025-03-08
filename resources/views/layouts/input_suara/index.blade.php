@extends('main')
@section('content')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <div class="main-content">
        <div class="card text-center">
            <h5 class="card-header">Input Suara Calon Wali Kota Tasikmalaya</h5>
        </div>

        @if (session()->has('success'))
            <div class="alert alert-success mt-2" role="alert">
                {{ session('success') }}
            </div>
        @endif  

        <div class="col-lg-12 my-4">
            <div class="card">
                <div class="card-body row g-4">
                    <div class="col-md-6 pe-md-4 card-separator">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <h5 class="mb-4">Pilih TPS</h5>
                            <small>Pastikan TPS yang dipilih benar!</small>
                        </div>
                        <div class="d-flex justify-content-between" style="position: relative;">
                            <div class="col-12">
                                <select class="form-select mt-4" name="tps" id="tps" required>
                                    <option value="0" selected>{{ old('tps', 'Pilih TPS') }}</option>
                                </select>

                                <div class="col-12 border border-3 rounded pt-2 mt-4 px-3">
                                    <p>Kecamatan: <b id="txt-kecamatan"></b></p>
                                    <p>Kelurahan: <b id="txt-kelurahan"></b></p>
                                    <p>TPS: <b id="txt-tps"></b></p>
                                    <p>Total Dpt: <b id="txt-dpt"></b></p>
                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="col-md-6 ps-md-4">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <h5 class="mb-1">Input Perolehan Suara</h5>
                            <small>Jangan Sampai Keliru!</small>
                        </div>
                        <div class="d-flex justify-content-between" style="position: relative;">
                            <div class="col-12">
                                {{-- <select class="form-select mt-4" name="calon_walkot" id="calon_walkot" required>
                                    <option selected value="0" disabled>
                                        {{ old('calon_walkot', 'Pilih Calon Walikota') }}</option>
                                    @foreach ($calons as $data)
                                        <option value="{{ $data->id }}">{{ $data->name }}</option>
                                    @endforeach
                                </select>

                                <div class="card mt-4 mx-auto " id="walkot" style="width: 70%;">

                                </div> --}}

                                {{-- <form action="{{ route('input-suara/store') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <input type="text" name="caleg_id" id="walkotValue" value="0" hidden>
                                    <input type="text" name="tps_id" id="tpsValue" value="0" hidden>
                                    <div class="form-group col-md-12">
                                        @php $listIdCalon = []; @endphp
                                        @if($calons->isEmpty())
                                            <p class="text-center">Belum Ada Calon</p>
                                        @else
                                            @foreach($calons as $data)
                                                <label for="tps">{{ $data->name }}</label>
                                                <input type="number" class="form-control mb-3" name="total_suara[]" id="{{ 'total_suara_' . $data->id }}"
                                                    required placeholder="Masukan Perolehan Suara">
                                                @php array_push($listIdCalon, $data->id) @endphp
                                            @endforeach
                                            <label for="tps" class="mt-4">Masukan Bukti Surat Pleno</label>
                                            <input type="file" class="form-control mb-2" name="bukti_pleno" id="total-suara"
                                                required placeholder="Masukan Perolehan Suara">

                                            <button class="btn btn-primary ml-3 mt-2" onclick="validateInputSuara({{ $listIdCalon }})" id="btnSubmit">Submit</button>
                                        @endif
                                        
                                    </div>
                                    <span id="suara_terakhir" style="display: none;"></span>
                                    <button class="btn btn-primary ml-3 mt-2" id="btnSubmit"
                                        >Submit</button>
                                </form> --}}

                                <input type="text" name="caleg_id" id="walkotValue" value="0" hidden>
                                <input type="text" name="tps_id" id="tpsValue" value="0" hidden>

                                <div class="form-group col-md-12">
                                    @php $listIdCalon = []; @endphp
                                    @if($calons->isEmpty())
                                        <p class="text-center">Belum Ada Calon</p>
                                    @else
                                        @foreach($calons as $data)
                                            <label for="tps">{{ $data->name }}</label>
                                            <input type="number" class="form-control mb-3" name="total_suara[]" id="{{ 'total_suara_' . $data->id }}"
                                                 required placeholder="Masukan Perolehan Suara">
                                            @php array_push($listIdCalon, $data->id) @endphp
                                        @endforeach
                                        <label for="tps" class="mt-4">Masukan Bukti Surat C1</label>
                                        <input type="file" class="form-control mb-2" name="bukti_pleno" id="bukti_pleno"
                                             placeholder="Masukan Perolehan Suara">

                                        <button class="btn btn-primary ml-3 mt-2" id="btnSubmit">Submit</button>
                                    @endif
                                    
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        showLoading();
        var inputWalkot = document.getElementById("calon_walkot");
        var inputTps = document.getElementById("tps");
        var btnSubmit = document.getElementById("btnSubmit");
        var textSuaraTerakhir = document.getElementById("suara_terakhir");

        let walkotValue = document.getElementById("walkotValue");
        let tpsValue = document.getElementById("tpsValue");

        function getsuaraTerakhir(idWalkot, idTps) {
            $.ajax({
                url: "{{ route('getSuaraCalon') }}",
                type: "POST",
                data: {
                    calon: idWalkot,
                    tpsId: idTps,
                    _token: '{{ csrf_token() }}'
                },
                dataType: 'json',
                success: function(result) {
                    textSuaraTerakhir.style.display = "block"
                    if (result.length === 0) {
                        $("#suara_terakhir").text(`Suara Terakhir Calon di Tps: 0`)
                    } else {
                        $("#suara_terakhir").text(`Suara Terakhir Calon di Tps: ${result[0].total_suara}`)
                    }
                }
            })

        }

        function validateInputSuara(listIdCalon) {
            console.log(listIdCalon)
            let allInputsFilled = true;
            let formData = new FormData();
            formData.append('tps_id', tpsValue.value)

            // Check each input field based on the IDs generated in the listIdCalon array
            listIdCalon.forEach(id => {
                let inputVal = $(`#total_suara_${id}`).val();
                if (inputVal === "" || inputVal === null) {
                    allInputsFilled = false;
                    $(`#total_suara_${id}`).addClass('is-invalid');
                } else {
                    $(`#total_suara_${id}`).removeClass('is-invalid');
                    formData.append(`total_suara[]`, inputVal); // Append values to FormData
                    formData.append('id_caleg[]', id);
                }
            });

            // Check file input
            let buktiPleno = $('#bukti_pleno')[0].files[0];
            if (!buktiPleno) {
                allInputsFilled = false;
                $('#bukti_pleno').addClass('is-invalid');
            } else {
                $('#bukti_pleno').removeClass('is-invalid');
                formData.append('bukti_pleno', buktiPleno);
            }

            if (allInputsFilled) {
                // Send the data using AJAX
                $.ajax({
                    url: "{{ route('input-suara/walkot/store') }}", // Change this to your route
                    method: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include CSRF token for Laravel
                    },
                    success: function(response) {
                        Swal.fire({
                            title: 'Success!',
                            text: 'Data Suara Berhasil Disimpan',
                            icon: 'success',
                            type: 'success',
                            customClass: {
                                confirmButton: 'btn btn-primary'
                            },
                            buttonsStyling: false
                        });

                        listIdCalon.forEach(id => {
                            let inputSuara = document.getElementById(`total_suara_${id}`)
                            inputSuara.value = '';
                        });

                        // Setel kembali opsi select ke default
                        $('#tps').val('0').trigger('change'); 

                        // Bersihkan elemen display data
                        $('#txt-kecamatan').text('');
                        $('#txt-kelurahan').text('');
                        $('#txt-tps').text('');
                        $('#txt-dpt').text('');
                    },
                    error: function(xhr) {
                        console.log(xhr.responseJSON); // Log response for debugging
                        if (xhr.status === 422) {
                            // Menampilkan error dari server
                            let errors = xhr.responseJSON.errors;
                            let errorMessages = Object.values(errors).flat().join('\n');
                            Swal.fire({
                                icon: "error",
                                title: "Oops...",
                                text: errorMessages,
                            });
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "Oops...",
                                text: "An error occurred while submitting the data.",
                            });
                        }
                    }
                });
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Tolong Lengkapi data input suara para calon",
                });
            }
        }

        // Attach event listener to the submit button
        $('#btnSubmit').on('click', function() {
            let listIdCalon = @json($listIdCalon);
            console.log(tpsValue.value.length)
            if(tpsValue.value !='0' && tpsValue.value.length != 0){
                validateInputSuara(listIdCalon);
            }else{
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Pilih TPS Terlebih Dahulu",
                });

            }
        });

        $(document).ready(function() {
            hideLoading();


            $('#calon_walkot').select2();
            $('#tps').select2({
                placeholder: 'Pilih Tps',
                allowClear: true,
                ajax: {
                    url: "{{ route('getTps') }}",
                    type: "POST",
                    delay: 250,
                    dataType: 'json',
                    data: function(params) {
                        return {
                            name: params.term,
                            "_token": "{{ csrf_token() }}",
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    id: ' ' + item.id,
                                    text: 'TPS ' + item.tps + ' - ' + 'Kelurahan ' + item
                                        .kelurahans.nama_kelurahan + ' - ' + 'Kecamatan ' + item
                                        .kelurahans.kecamatans.nama
                                }
                            })
                        };
                    },
                },
            });

            $('#tps').on('change', function() {
                var valueTps = this.value;
                var trimTps = valueTps.trim();
                tpsValue.value = trimTps;

                // if (inputWalkot.value != 0 && inputTps.value != 0) {
                //     getsuaraTerakhir(inputWalkot.value, trimTps)
                //     btnSubmit.style.display = "block"
                // }

                $.ajax({
                    url: "{{ route('getTpsSatuan') }}",
                    type: "POST",
                    data: {
                        idTps: trimTps,
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(result) {
                        if (result.length === 0) {
                            $("#txt-kecamatan").html("Data DPT Belum diinput");
                            $("#txt-kelurahan").html("Data DPT Belum diinput");
                            $("#txt-tps").html("Data DPT Belum diinput");
                            $("#txt-dpt").html("Data DPT Belum diinput");
                        } else {
                            $("#txt-kecamatan").html(result[0].kelurahans.kecamatans.nama);
                            $("#txt-kelurahan").html(result[0].kelurahans.nama_kelurahan);
                            $("#txt-tps").html(result[0].tps);
                            $("#txt-dpt").html(result[0].totdpt);
                        }

                    }
                })
            });

            $('#calon_walkot').on('change', function() {
                var valueWalkot = this.value;
                walkotValue.value = this.value

                if (inputWalkot.value != 0 && inputTps.value != 0) {
                    getsuaraTerakhir(valueWalkot, inputTps.value)
                    btnSubmit.style.display = "block"
                }

                $.ajax({
                    url: "{{ route('getWalkot') }}",
                    type: "POST",
                    data: {
                        idWalkot: valueWalkot,
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(result) {
                        $("#walkot").html(` <!-- Card Header with Image -->
                                    <div class="card-header" style="padding: 0;">
                                        <img src="{{ asset('uploads/${result[0].gambar_walkot}') }}" class="card-img-top" alt="Header Image">
                                    </div>

                                    <div class="card-body mt-4">
                                        <h5 class="card-title">${result[0].name}</h5>
                                        <p class="card-text">${result[0].partai}</p>
                                    </div>`);
                    }
                })
            });
        });
    </script>
@endsection
