@extends('layouts.app')

@section('content')
<div class="container-fluid vh-100">
  <div class="row h-100">

    <!-- Lado izquierdo con imagen -->
    <div class="col-md-6 d-none d-md-flex left-side p-4">
      <!-- Contenido -->
      <div class="left-side-content">
        <h1 class="display-5 fw-bold">¡Hola de nuevo!</h1>
        <p class="lead">Aquí puedes iniciar sesión o registrarte si no tienes usuario</p>
      </div>
    </div>

    <!-- Lado derecho con formulario -->
    <div class="col-md-6 d-flex align-items-center justify-content-center bg-light p-4">
      <div class="form-container">

        <!-- Tabs -->
        <ul class="nav nav-tabs custom-tabs justify-content-center flex-wrap mb-4" id="interesTabs" role="tablist">
          <li class="nav-item" role="presentation">
            <a class="nav-link active" id="login-tab" data-bs-toggle="tab" href="#loginTab" type="button" role="tab">Inicio de sesión</a>
          </li>
          <li class="nav-item" role="presentation">
            <a class="nav-link" id="register-tab" data-bs-toggle="tab" href="#registerTab" type="button" role="tab">Registro</a>
          </li>
        </ul>

        <div class="tab-content" id="tabContent">
          <!-- TAB: LOGIN -->
          <div class="tab-pane fade show active" id="loginTab" role="tabpanel">
            <!-- Tipo de inicio -->
            <div class="mb-3">
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="login_type" id="cuilRadio" value="cuil">
                <label class="form-check-label" for="cuilRadio">CUIL</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="login_type" id="emailRadio" value="email" checked>
                <label class="form-check-label" for="emailRadio">Correo</label>
              </div>
            </div>

            <!-- Formulario -->
            <form method="POST" action="" id="loginForm">
              @csrf

              <div class="mb-3">
                <label for="userInput" class="form-label" id="userLabel">Correo *</label>
                <input type="email" class="form-control" id="userInput" name="email" required>
              </div>

              <div class="mb-3">
                <label for="passwordInput" class="form-label">Contraseña *</label>
                <div class="input-group">
                  <input type="password" class="form-control" id="passwordInput" name="password" required>
                  <button class="btn btn-show-password" type="button" onclick="togglePassword()">👁️</button>
                </div>
              </div>

              <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                <label class="form-check-label" for="remember">Recordar contraseña</label>
              </div>

              <div class="mb-3 text-end">
                <a href="">¿Se te olvidó tu contraseña?</a>
              </div>

              <button type="submit" class="btn btn-outline-success w-100" id="loginBtn" disabled>Iniciar sesión</button>

              <div class="text-center my-3">— o —</div>

              <button type="button" class="btn btn-outline-success w-100">
                <i class="bi bi-wallet2 me-2"></i>Ingresar con wallet
              </button>
            </form>
          </div>

          <!-- TAB: REGISTRO -->
          <div class="tab-pane fade" id="registerTab" role="tabpanel">
            <p class="text-muted">Valida tu identidad con alguno de los siguientes métodos</p>
            <div class="d-grid gap-3">

              <!-- Botón ARCA -->
              <a href="#" class="btn d-flex align-items-center text-white fw-semibold" style="background: linear-gradient(to right, #003366 25%, #007bce 75%); border-radius: 50px; height: 48px;">
                <div class="d-flex justify-content-center align-items-center" style="width: 25%;">
                  <svg width="82.084" height="78.432" viewBox="0 0 82.084 78.432" class="v-icon__component theme--light" style="max-height: 22px;">
                    <path id=" Trazado_2014" data-name="Trazado 2014" d="M1011.154,935.929c-7.024-15.566-14.188-31.069-20.867-46.786-.926-2.179-2.058-4.076-4.327-5.063H966.918A9.729,9.729,0,0,0,962.735,889q-11.39,25.1-22.888,50.161-4.263,9.331-8.539,18.656v1.973c.888,1.477,1.894,2.727,3.875,2.723,12.521-.02,25.042.009,37.561-.029a4.776,4.776,0,0,0,4.637-3.08c1.248-2.859,2.561-5.691,3.771-8.566a1.589,1.589,0,0,1,1.74-1.131c7.783.036,15.57-.093,23.35.064a6.964,6.964,0,0,0,7.008-8.464A24.443,24.443,0,0,0,1011.154,935.929Zm-37.389,21.124a1.651,1.651,0,0,1-1.786,1.109c-5.808-.035-11.618-.018-17.427-.018s-11.619-.014-17.428.015c-.94.006-1.249-.079-.775-1.113q14.887-32.476,29.726-64.977c1.857-4.056.884-3.474,5.5-3.5,4.08-.026,8.158.022,12.237-.023a2.026,2.026,0,0,1,2.158,1.406q4.087,9.509,8.277,18.973a2.518,2.518,0,0,1-.054,2.224Q983.951,934.088,973.765,957.053Zm31.976-11.558c-7-.138-14.006-.053-21.01-.036-.764,0-1.169-.018-.738-.979q6.452-14.386,12.847-28.8c.033-.074.087-.138.26-.4,1.574,3.461,3.071,6.738,4.558,10.017q3.281,7.234,6.553,14.474c.187.411.383.818.552,1.238C1009.886,943.777,1008.357,945.547,1005.741,945.495Z" transform="translate(-931.309 -884.08)" fill="#fbfbfd"></path>
                  </svg>
                </div>
                <div class="w-100 text-center">Registrate con ARCA</div>
              </a>

              <!-- Botón Mi Argentina -->
              <a href="#" class="btn d-flex align-items-center text-white fw-semibold" style="background: linear-gradient(to right, #003366 25%, #007bce 75%); border-radius: 50px; height: 48px;">
                <div class="d-flex justify-content-center align-items-center" style="width: 25%;">
                  <svg width="72.348" height="89.061" viewBox="0 0 72.348 89.061" class="v-icon__component theme--light" style="max-height: 22px;">
                    <path id="Trazado_2022" data-name="Trazado 2022" d="M606.114,947.944c6.886.715,10.132,5.094,10.657,10.114a7.639,7.639,0,0,0,.306,1.164c.011,8.089-.013,16.178.067,24.267.014,1.467-.394,1.927-1.863,1.855-2.421-.119-4.855-.1-7.279-.006-1.242.047-1.636-.3-1.623-1.585.065-6.572.032-13.146.033-19.719,0-.3,0-.606,0-.91-.032-2.127-.174-4.328-2.406-5.318a6.873,6.873,0,0,0-6.776.812,2.416,2.416,0,0,0-1.169,2.27c.054,7.584,0,15.169.064,22.752.01,1.322-.323,1.764-1.684,1.7-2.473-.11-4.956-.089-7.43-.006-1.194.04-1.55-.33-1.541-1.534.055-6.623.026-13.247.026-19.87,0-.3,0-.608,0-.911-.029-2.238-.186-4.451-2.672-5.437-2.274-.9-5.711.147-7.342,2.068a2.982,2.982,0,0,0-.54,2.139c0,7.281-.031,14.562.028,21.842.01,1.321-.343,1.765-1.7,1.709-2.575-.106-5.157-.059-7.735-.017-.915.016-1.375-.174-1.373-1.237q.044-17.139,0-34.281c0-1.046.434-1.275,1.363-1.255,2.072.047,4.148.075,6.217-.009,1.065-.044,1.379.443,1.574,1.356.528,2.482.566,2.487,2.564.856,4.7-3.841,14.206-3.948,17.525,1.245.293.459.612.63,1.08.166C597.71,948.967,601.711,947.9,606.114,947.944Z" transform="translate(-564.163 -931.973)" fill="#fdfefe"></path>
                    <path id="Trazado_2023" data-name="Trazado 2023" d="M612.065,1034.308c5.057,0,10.113.007,15.17-.008.9,0,1.713.063,2.2.981.512.958-.008,1.657-.561,2.326q-7.495,9.05-15.013,18.08c-1.108,1.329-2.2,1.376-3.28.083q-7.634-9.134-15.216-18.314a1.887,1.887,0,0,1-.469-2.089,1.922,1.922,0,0,1,2-1.068C601.951,1034.318,607.008,1034.308,612.065,1034.308Z" transform="translate(-576.798 -967.652)" fill="#fdfefe"></path>
                    <path id="Trazado_2024" data-name="Trazado 2024" d="M666.793,966.622c.04-5.3.122-10.605.089-15.906-.009-1.367.35-1.853,1.771-1.788,2.52.112,5.051.088,7.575.009,1.145-.036,1.451.338,1.443,1.46-.037,5.707.007,11.414.023,17.121-.019,5.505-.077,11.01-.028,16.514.011,1.279-.248,1.783-1.648,1.716-2.52-.122-5.052-.095-7.574-.008-1.222.042-1.544-.39-1.532-1.556.05-5.3.027-10.607.018-15.911C666.929,967.721,666.84,967.172,666.793,966.622Z" transform="translate(-606.567 -932.377)" fill="#fdfefe"></path>
                    <path id="Trazado_2025" data-name="Trazado 2025" d="M671.341,931.912a8.132,8.132,0,0,1-4.1-.621c-2.667-1.284-2.8-3.721-2.588-6.161a4.473,4.473,0,0,1,3.38-3.835,10.044,10.044,0,0,1,6.253-.13c2.787.823,3.964,2.876,3.67,6.127a5.045,5.045,0,0,1-4.8,4.611A17,17,0,0,1,671.341,931.912Z" transform="translate(-605.654 -920.729)" fill="#fdfefe"></path>
                  </svg>
                </div>
                <div class="w-100 text-center">Registrate con Mi Argentina</div>
              </a>

              <!-- Botón DNI -->
              <a href="#" class="btn d-flex align-items-center text-white fw-semibold" style="background: linear-gradient(to right, #003366 25%, #007bce 75%); border-radius: 50px; height: 48px;">
                <div class="d-flex justify-content-center align-items-center" style="width: 25%;">
                  <svg width="63.193" height="78.697" viewBox="0 0 63.193 78.697" class="v-icon__component theme--light" style="max-height: 22px;">
                    <g transform="translate(0 0)">
                      <g transform="translate(0 0)">
                        <path d="M334.41,897.262a52.836,52.836,0,0,1,3.254-13.139c3.686-8.6,9.064-15.691,17.854-19.594,9.674-4.3,18.677-2.6,26.818,3.725,8.251,6.413,12.674,15.215,14.36,25.381,1.96,11.816-.046,22.934-6.738,32.994-4.957,7.451-11.639,12.492-20.638,14.067a29.036,29.036,0,0,1-9.109-.479,31.957,31.957,0,0,1-6.646-2.544c-7.685-4.194-12.542-10.8-15.905-18.68a53.239,53.239,0,0,1-3.25-13.134A48.951,48.951,0,0,1,334.41,897.262Zm2.8,4.249a40.474,40.474,0,0,0,.363,5.541c1.172,8.952,4.485,16.885,10.99,23.308,4.719,4.66,10.315,7.59,17.013,7.62,8.563.038,15.179-4.141,20.292-10.679,6.94-8.875,9.432-19.1,8.312-30.244-.995-9.906-4.744-18.517-12.164-25.349-9.508-8.757-23.04-8.679-32.494.142C340.916,879.883,337.526,890.055,337.213,901.511Z" transform="translate(-334.22 -862.153)" fill="#fbfbfd"></path>
                        <path d="M352.424,907.339q0-11.088,0-22.178c0-3.317.291-3.62,3.509-3.617,4.884,0,9.772-.109,14.651.049,5.4.175,10.467,1.459,14.557,5.366a16.929,16.929,0,0,1,5.023,10.963,21.244,21.244,0,0,1-1.709,11.174,3.232,3.232,0,0,0-.171,2.238c1.2,5.036,2.267,10.106,3.556,15.118a4.577,4.577,0,0,1-.786,4.087,5.3,5.3,0,0,1-5.094,2.656c-10.122-.166-20.25-.07-30.375-.07-2.742,0-3.159-.425-3.159-3.248Q352.421,918.607,352.424,907.339Zm2.418.01c0,7.272.019,14.543-.019,21.815-.005,1.164.238,1.735,1.564,1.73q16.167-.06,32.333-.007c1.3,0,1.626-.352,1.3-1.684-1.405-5.782-2.68-11.595-4.057-17.385a4.83,4.83,0,0,1,.241-3.431,18.652,18.652,0,0,0,1.631-6.359c.9-9.388-4.161-16.087-13.475-17.661-5.853-.989-11.761-.384-17.641-.593-1.507-.053-1.921.472-1.908,1.94C354.877,892.925,354.841,900.138,354.841,907.349Z" transform="translate(-339.65 -867.927)" fill="#fbfbfd"></path>
                        <path d="M357.544,877.47c1.057-1.357,1.926-2.407,3.2-2.4,6.059.034,12.144-.483,18.143.94,12.269,2.914,18.891,12.93,17.343,26.114a4.6,4.6,0,0,1-.239,1.4c-2.282,4.8-.617,9.428.615,14.047a5.643,5.643,0,0,1-.983,5.421,5.68,5.68,0,0,1-.865-2.571c-.795-3.3-1.468-6.634-2.295-9.928a6.552,6.552,0,0,1,.21-3.814c2.151-6.63,2.158-13.219-1.42-19.364-3.4-5.833-8.921-8.544-15.427-9.475-5.219-.747-10.47-.218-15.7-.372C359.419,877.453,358.713,877.47,357.544,877.47Z" transform="translate(-341.178 -865.987)" fill="#fbfbfd"></path>
                        <path d="M348.51,930.617c-1.354-2.142-2.3-3.848-2.282-6.1q.138-15.292,0-30.585c-.02-2.253.926-3.957,2.281-6.08Z" transform="translate(-337.802 -869.819)" fill="#fbfbfd"></path>
                        <path d="M372.966,958.015c4.527,0,9.054-.008,13.582.011.439,0,1.068-.256,1.282.282.232.581-.475.707-.774,1.032a3.386,3.386,0,0,1-2.746.975q-11.348-.048-22.7.008a3.815,3.815,0,0,1-2.893-1.084c-.281-.275-.835-.428-.63-.936.2-.485.731-.277,1.114-.279C363.792,958.01,368.38,958.015,372.966,958.015Z" transform="translate(-341.328 -890.737)" fill="#fbfbfd"></path>
                        <path d="M367.412,871.19c6.4-3.605,17.7-1.869,21.5,3.4C381.98,870.973,374.935,871.118,367.412,871.19Z" transform="translate(-344.122 -864.282)" fill="#fbfbfd"></path>
                        <path d="M342.806,902.643v21.97A36.06,36.06,0,0,1,342.806,902.643Z" transform="translate(-336.27 -874.232)" fill="#fbfbfd"></path>
                        <path d="M366.834,964.4h17.223C380.562,967.2,371.612,967.428,366.834,964.4Z" transform="translate(-343.949 -892.657)" fill="#fbfbfd"></path>
                        <path d="M415.322,924.687c-.883-3.345-1.815-6.22-.56-9.379.9-2.263.837-4.769.772-7.317C417,910.213,416.91,920.287,415.322,924.687Z" transform="translate(-358.066 -875.828)" fill="#fbfbfd"></path>
                        <path d="M358.541,909.217c0-6.5.042-13-.031-19.5-.017-1.5.448-1.962,1.928-1.914,4.7.154,9.4-.056,14.11.224,7.561.45,12.579,5.5,12.855,13.1a16.866,16.866,0,0,1-1.931,9.145,3.457,3.457,0,0,0-.266,2.72c1.284,5.326,2.44,10.682,3.742,16,.309,1.259.194,1.7-1.215,1.689q-13.765-.068-27.529,0c-1.393.008-1.7-.511-1.686-1.782C358.567,922.339,358.541,915.779,358.541,909.217Zm2.443-.158c0,5.783.037,11.565-.027,17.346-.015,1.405.418,1.858,1.839,1.846q10.9-.091,21.8,0c1.344.01,1.762-.224,1.392-1.67-1.151-4.5-2.122-9.041-3.262-13.541a4.178,4.178,0,0,1,.394-3.4,15.007,15.007,0,0,0,1.854-8.6c-.276-5.806-3.522-9.561-9.284-10.5-4.318-.7-8.679-.154-13.014-.36-1.346-.065-1.732.373-1.716,1.709C361.023,897.613,360.984,903.337,360.984,909.059Z" transform="translate(-341.466 -869.803)" fill="#fbfbfd"></path>
                        <path d="M366.158,911.355c0-4.888.036-9.777-.024-14.665-.016-1.3.342-1.817,1.709-1.761,3.149.128,6.3-.087,9.454.262,4.335.481,6.836,3.154,7.091,7.531.224,3.826-.323,7.285-3.858,9.558-.424.273-.638.526-.208.966,2.079,2.129,2.494,4.932,3.1,7.661q.563,2.527,1.176,5.039c.29,1.189.186,1.847-1.371,1.851-4.929.014-4.9.072-5.686-4.854a31.238,31.238,0,0,0-1.737-7.274c-.472-1.126-1.107-1.741-2.435-1.517-1.471.247-.9,1.347-.917,2.1q-.089,4.916-.007,9.836c.02,1.175-.264,1.687-1.583,1.7-4.709.05-4.708.1-4.708-4.635Zm6.1-6.573c0,1.249.073,2.5-.021,3.747-.1,1.293.51,1.477,1.6,1.362,3.412-.362,4.48-1.7,4.469-5.744-.011-3.542-1.088-4.575-4.584-4.547-1.053.007-1.535.322-1.479,1.433C372.307,902.28,372.259,903.533,372.259,904.782Z" transform="translate(-343.74 -871.93)" fill="#fbfbfd"></path>
                      </g>
                    </g>
                  </svg>
                </div>
                <div class="w-100 text-center">Registrate con DNI</div>
              </a>

            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

<script>
  function togglePassword() {
    const input = document.getElementById("passwordInput");
    input.type = input.type === "password" ? "text" : "password";
  }

  // Cambiar label y tipo del input dinámicamente
  document.querySelectorAll('input[name="login_type"]').forEach(radio => {
    radio.addEventListener("change", function() {
      const label = document.getElementById("userLabel");
      const input = document.getElementById("userInput");

      if (this.value === "cuil") {
        label.textContent = "CUIL *";
        input.type = "text";
        input.name = "cuil";
        input.placeholder = "Ej: 20123456789";
      } else {
        label.textContent = "Correo *";
        input.type = "email";
        input.name = "email";
        input.placeholder = "";
      }

      validateLoginFields();
    });
  });

  // Validación en tiempo real
  const userInput = document.getElementById("userInput");
  const passwordInput = document.getElementById("passwordInput");
  const loginBtn = document.getElementById("loginBtn");

  userInput.addEventListener("input", validateLoginFields);
  passwordInput.addEventListener("input", validateLoginFields);

  function validateLoginFields() {
    if (userInput.value.trim() !== "" && passwordInput.value.trim() !== "") {
      loginBtn.removeAttribute("disabled");
    } else {
      loginBtn.setAttribute("disabled", true);
    }
  }
</script>
@endsection