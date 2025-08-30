<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Join Zoom Meeting' }}</title>
    <meta http-equiv="origin-trial" content="">
    <!-- Zoom Web SDK CSS -->
    <link type="text/css" rel="stylesheet" href="https://source.zoom.us/3.9.2/css/bootstrap.css" />
    <link type="text/css" rel="stylesheet" href="https://source.zoom.us/3.9.2/css/react-select.css" />
    <style>
        @if ($viewType === 'full')
            /* Full page styles */
            body {
                margin: 0;
                padding: 0;
                overflow: hidden;
            }

            #zmmtg-root {
                background-color: #2D2D2D;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
            }
        @else
            /* Component styles */
            body {
                margin: 0;
                padding: 0;
            }

            .card {
                border: 1px solid #ddd;
                border-radius: .25rem;
                margin: 2rem;
                font-family: sans-serif;
            }

            .card-header {
                padding: .75rem 1.25rem;
                margin-bottom: 0;
                background-color: rgba(0, 0, 0, .03);
                border-bottom: 1px solid rgba(0, 0, 0, .125);
            }

            .card-body {
                flex: 1 1 auto;
                padding: 1.25rem;
            }

            #meetingSDKElement {
                width: 100%;
                height: 600px;
            }
        @endif
    </style>
</head>

<body>
    @if ($viewType === 'full')
        <div id="zmmtg-root"></div>
    @else
        <div class="card">
            <div class="card-header">
                <h4>Meeting: {{ $title ?? 'Zoom Meeting' }}</h4>
            </div>
            <div class="card-body">
                <div id="meetingSDKElement">
                    <!-- Zoom Meeting SDK Rendered Here -->
                </div>
            </div>
        </div>
    @endif

    <!-- Dependensi Zoom Web SDK -->
    <script src="https://source.zoom.us/3.9.2/lib/vendor/react.min.js"></script>
    <script src="https://source.zoom.us/3.9.2/lib/vendor/react-dom.min.js"></script>
    <script src="https://source.zoom.us/3.9.2/lib/vendor/redux.min.js"></script>
    <script src="https://source.zoom.us/3.9.2/lib/vendor/redux-thunk.min.js"></script>
    <script src="https://source.zoom.us/3.9.2/lib/vendor/lodash.min.js"></script>

    <!-- Conditional SDK -->
    @if ($viewType === 'full')
        <script src="https://source.zoom.us/3.9.2/zoom-meeting-3.9.2.min.js"></script>
    @else
        <script src="https://source.zoom.us/3.9.2/zoom-meeting-embedded-3.9.2.min.js"></script>
    @endif

    <script>
        // Kumpulkan semua data dari PHP ke dalam satu objek JavaScript
        const meetingConfig = {
            sdkKey: @json($sdkKey),
            meetingNumber: parseInt(@json($meeting_number), 10),
            password: @json($password),
            userName: @json($userName),
            userEmail: @json($userEmail),
            signature: @json($signature),
            zakToken: @json($zakToken),
            leaveUrl: @json(url('/')),
            viewType: @json($viewType)
        };

        document.addEventListener('DOMContentLoaded', function() {
            if (meetingConfig.viewType === 'full') {
                // Full Page/Client View Initialization
                ZoomMtg.preLoadWasm();
                ZoomMtg.prepareWebSDK();

                ZoomMtg.init({
                    leaveUrl: meetingConfig.leaveUrl,
                    isSupportAV: true,
                    patchJsMedia: true, // From reference
                    leaveOnPageUnload: true, // From reference
                    success: function(success) {
                        console.log('ZoomMtg.init success:', success);
                        ZoomMtg.join({
                            signature: meetingConfig.signature,
                            sdkKey: meetingConfig.sdkKey,
                            meetingNumber: meetingConfig.meetingNumber,
                            passWord: meetingConfig.password,
                            userName: meetingConfig.userName,
                            userEmail: meetingConfig.userEmail,
                            zak: meetingConfig.zakToken, // Gunakan ZAK token di sini
                            // The 'role' is embedded in the signature.
                            // tk (registrantToken) and zak are for other auth methods, not needed here.
                            success: (joinSuccess) => {
                                console.log('ZoomMtg.join success:', joinSuccess);
                            },
                            error: (joinError) => {
                                console.error('Error joining meeting:', joinError);
                            }
                        });
                    },
                    error: (initError) => {
                        console.error('Error initializing Zoom SDK:', initError);
                    }
                });
            } else {
                // Component/Embedded View Initialization
                const client = ZoomMtgEmbedded.createClient();
                let meetingSDKElement = document.getElementById('meetingSDKElement');

                client.init({
                    debug: true, // Set to true for development debugging
                    zoomAppRoot: meetingSDKElement,
                    leaveUrl: meetingConfig.leaveUrl,
                    isSupportAV: true,
                }).then(() => {
                    client.join({
                        signature: meetingConfig.signature,
                        sdkKey: meetingConfig.sdkKey,
                        meetingNumber: meetingConfig.meetingNumber,
                        password: meetingConfig.password,
                        userName: meetingConfig.userName,
                        userEmail: meetingConfig.userEmail,
                        zak: meetingConfig.zakToken, // Gunakan ZAK token di sini juga
                    }).catch((error) => {
                        console.error(error);
                    });
                }).catch((error) => {
                    console.error(error);
                });
            }
        });
    </script>
</body>

</html>
