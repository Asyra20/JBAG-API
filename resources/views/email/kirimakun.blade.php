<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JBAG</title>
</head>

<body style="width: 100%; background-color: #FFC639; font-family: 'Helvetica', sans-serif; display: flex; justify-content: center; align-items: center; margin: 0;">
    <div class="card-container" style="background-color: #131A2A; padding: 30px; margin-top: 30px; margin-left: auto; margin-right: auto; margin-bottom: 30px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); width: 550px; text-align: center;">
        <div class="card-header" style="font-size: 38px; color: #FFFAFF; font-family: 'Helvetica', sans-serif; margin-bottom: 20px;">
            JBAG
        </div>
        <div class="card-body" style="font-family: 'Helvetica', sans-serif; color: #FFFAFF;">
            <div class="seller-name" style="color: #FFC639; font-size: 28px; margin-bottom: 20px;">
                {{ $nama_penjual }}
            </div>

            @foreach ($data as $item)
            <div class="divider" style="width: 100%; height: 1px; background-color: #FFC639; margin: 20px 0;"></div>
            <div class="list-item" style="margin: 10px 0; display: flex; align-items: center; justify-content: start; font-size: 16px; vertical-align: middle; overflow: hidden;">
                <span style="display: inline-block; width: 80px; font-size: 18px; margin-right: 10px; text-align: left; vertical-align: middle; white-space: nowrap;">
                    Judul
                </span>
                <div style="margin-right: 5px; vertical-align: middle;">:</div>
                <p style="flex: 1; margin: 0; text-align: left; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">
                    {{ $item['judul'] }}
                </p>
            </div>
            <div class="list-item" style="margin: 10px 0; display: flex; align-items: center; justify-content: start; font-size: 16px; vertical-align: middle; overflow: hidden;">
                <span style="display: inline-block; width: 80px; font-size: 18px; margin-right: 10px; text-align: left; vertical-align: middle; white-space: nowrap;">
                    UID
                </span>
                <div style="margin-right: 5px; vertical-align: middle;">:</div>
                <p style="flex: 1; margin: 0; text-align: left; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">
                    {{ $item['uid_akun'] }}
                </p>
            </div>
            <div class="list-item" style="margin: 10px 0; display: flex; align-items: center; justify-content: start; font-size: 16px; vertical-align: middle; overflow: hidden;">
                <span style="display: inline-block; width: 80px; font-size: 18px; margin-right: 10px; text-align: left; vertical-align: middle; white-space: nowrap;">
                    Email
                </span>
                <div style="margin-right: 5px; vertical-align: middle;">:</div>
                <a href="mailto:{{ $item['email_akun'] }}" style="text-decoration: none; color: inherit;">
                    {{ $item['email_akun'] }}
                </a>
            </div>
            <div class="list-item" style="margin: 10px 0; display: flex; align-items: center; justify-content: start; font-size: 16px; vertical-align: middle; overflow: hidden;">
                <span style="display: inline-block; width: 80px; font-size: 18px; margin-right: 10px; text-align: left; vertical-align: middle; white-space: nowrap;">
                    Password
                </span>
                <div style="margin-right: 5px; vertical-align: middle;">:</div>

                <p style="flex: 1; margin: 0; text-align: left; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">
                    {{ $item['password_akun'] }}
                </p>
            </div>
            @endforeach
        </div>
    </div>
</body>

</html>