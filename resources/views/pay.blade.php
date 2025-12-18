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
            padding: 24px;
        }

        .container {
            border: 1px solid black;
            padding: 24px;
            border-radius: 4px;
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
    <title>PARKEY - Konfirmasi Payment</title>
</head>

<body>
    <div class="container">
        <h2>Bayar ke Sandbox Midtrans</h2>
        <div class="" style=" text-align: center; padding: 12px 0px;">
            <image src="{{ $data->payment->payment_string }}" style="width: 256px; height: 256px;" />
        </div>
        <hr>
        <div class="container_box">
            <h4>Data Pembayan</h4>
            <div class="box" style="cursor: pointer;" onclick="copyText()">
                <p>Link Pembayaran :</p>
                <p id="value_box" class="data_payment">{{ $data->payment->payment_string }} </p>
            </div>
            <div class="box">
                <p>Invoice Pembayaran :</p>
                <p id="value_box">{{ $data->payment->invoice_id }} </p>
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
        <a href="https://simulator.sandbox.midtrans.com/v2/qris/index" style="text-decoration: none; color:black;">
            <button style="width: 100%;">
                Bayar Disini
            </button>
        </a>

    </div>
</body>
<script>
    function copyText() {
        const text = document.querySelector(".data_payment").innerText;
        navigator.clipboard.writeText(text)
            .then(() => {
                alert("Link Pembayaran Berhasil Di Copy!");
            })
            .catch(err => {
                console.error("Gagal copy:", err);
            });
    }
</script>

</html>
