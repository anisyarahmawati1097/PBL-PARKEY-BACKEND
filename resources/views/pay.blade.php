<!DOCTYPE html>
<html lang="en">

<head>
    <style>
        *,
        html {
            padding: 0;
            margin: 0;
        }

        body {
            display: flex;
            min-height: 100vh;
            justify-content: center;
            align-items: center;
        }

        .container {
            border: 1px solid black;
            padding: 24px;
            border-radius: 4px;
            width: 50%;
            background-color: rgb(0, 0, 0, .1)
        }

        .container h2 {
            margin-bottom: .5em;
            text-align: center;
        }

        .container .box {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 4px 0px;
            padding: 4px 0px;
            border-bottom: 1px solid black;
        }

        #value_box {
            padding: 2px 6px;
            font-weight: bold;
            text-wrap: wrap;
        }

        form {
            margin-top: 2em;
            display: flex;
            flex-direction: column;
        }

        form label {
            text-align: center;
            padding: 4px 0px;
        }

        form input {
            flex: 1;
            padding: 3px 6px;
            outline: none;
            font-size: 14px;
        }

     button {
            margin-top: 12px;
            flex: 1;
            padding: 6px;
            cursor: pointer;
        }

        .container_box {
            margin-top: 1em;
        }
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <div class="container">
        @if (session('data'))
            <h2>Bayar ke Sandbox Midtrans</h2>
            <hr>
            <div class="box">
                <p>Admin :</p>
                <p>{{ session('data')->admin->nama_admin }}</p>
            </div>
            <div class="container_box">
                <h4>Data Pembayan</h4>
                <div class="box">
                    <p>Link Pembayaran :</p>
                    <p id="value_box">{{ session('data')->payment->payment_string }} </p>
                </div>
                <div class="box">
                    <p>Invoice Pembayaran :</p>
                    <p id="value_box">{{ session('data')->payment->invoice_id }} </p>
                </div>
            </div>

            <div class="container_box">
                <h4>Data Parkir</h4>
                <div class="box">
                    <p>Parkir ID :</p>
                    <p id="value_box">{{ session('data')->payment->parkirs->parkir_id }} </p>
                </div>
                <div class="box">
                    <p>Masuk :</p>
                    <p id="value_box">{{ session('data')->payment->parkirs->masuk }} </p>
                </div>
                <div class="box">
                    <p>Keluar :</p>
                    <p id="value_box">{{ session('data')->payment->parkirs->keluar }} </p>
                </div>
            </div>
            <a href="https://simulator.sandbox.midtrans.com/v2/qris/index" style="text-decoration: none; color:black;">
                <button style="width: 100%;">
                    Bayar Disini
                </button>
            </a>
        @else
            <h2>Konfirmasi Pembayaran oleh Admin</h2>
            <hr>
            <div class="container_box">
                <h4>Data Kendaraan</h4>
                <div class="box">
                    <p>Plat Nomor :</p>
                    <p id="value_box">{{ $data->kendaraans->plat_nomor }} </p>
                </div>
                <div class="box">
                    <p>Warna :</p>
                    <p id="value_box">{{ strtoupper($data->kendaraans->jenis) }} </p>
                </div>
                <div class="box">
                    <p>Jenis :</p>
                    <p id="value_box">{{ strtoupper($data->kendaraans->jenis) }} </p>
                </div>
            </div>
            <div class="container_box">
                <h4>Lokasi</h4>
                <div class="box">
                    <p>Alamat :</p>
                    <p id="value_box">{{ $data->lokasi->nama_lokasi }} - {{ $data->lokasi->alamat_lokasi }} </p>
                </div>
            </div>
            <div class="container_box">
                <h4>Data Parkir</h4>
                <div class="box">
                    <p>Parkir ID :</p>
                    <p id="value_box">{{ $data->parkir_id }} </p>
                </div>
                <div class="box">
                    <p>Masuk :</p>
                    <p id="value_box">{{ $data->masuk }} </p>
                </div>
                <div class="box">
                    <p>Keluar :</p>
                    <p id="value_box">{{ $data->keluar }} </p>
                </div>
            </div>
            <form action="/pay" method="POST">
                @method('POST')
                @csrf
                <label for="username_admin">Masukan Username Admin ( Validasi Konfirmasi ) : </label>
                <input type="text" name="username_admin" id="username_admin">
                <input type="hidden" name="parkId" value="{{ $parkId }}">
                <button type="submit">Konfirmasi</button>
            </form>
        @endif
        @if (session('status') == 404)
            <p style="text-align: center; color: red; margin-top: 1em;">{{ session('message') }}</p>
        @endif
    </div>
</body>

</html>
