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
            background-color: rgb(0,0,0,.1)
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

        form button {
            margin-top: 12px;
            flex: 1;
            padding: 6px;
            cursor: pointer;
        }
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PARKEY - Konfirmasi Parkir</title>
</head>

<body>
    <div class="container">
        <h2>Konfirmasi Parkir oleh Admin</h2>
        <div class="box">
            <p>Plat Nomor :</p>
            <p id="value_box">{{ $data->plat_nomor }} </p>
        </div>
        <div class="box">
            <p>Warna :</p>
            <p id="value_box">{{ $data->warna }} </p>
        </div>
        <div class="box">
            <p>Jenis :</p>
            <p id="value_box">{{ $data->jenis }} </p>
        </div>
        <form action="/api/park" method="POST">
            @method('POST')
            <label for="username_admin">Masukan Nama Admin : </label>
            <input type="text" name="username_admin" id="username_admin">
            <input type="hidden" name="token_parkir" value="{{ $token_parkir }}">
            <button type="submit">Konfirmasi</button>
        </form>
    </div>
</body>

</html>
