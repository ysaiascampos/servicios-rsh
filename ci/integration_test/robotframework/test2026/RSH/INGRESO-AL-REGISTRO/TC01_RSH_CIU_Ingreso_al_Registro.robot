*** Settings ***
Suite Setup       Abrir Navegador
Suite Teardown    Cerrar Navegador
Task Timeout      10 minutes
Library           SeleniumLibrary
Resource          ../RESOURCES/Resource_${ENV}.resource


*** Variables ***
${RUN_CONSULTA}    17.041.337-7
${USUARIO_FINAL}   mmendoza
${PASSWORD_FINAL}  Ini2026mms


*** Test Cases ***
TC01_RSH_CIU_Ingreso_al_Registro
    Abrir Sitio
    Login Dummy Con RUN
    Crear Registro RSH
    Completar Direccion
    Completar Vivienda
    Continuar Flujo
    Completar Datos Pueblos Originarios
    Completar Datos Educacionales
    Completar Formulario de Salud
    Cerrar Sesion

*** Keywords ***
Abrir Navegador
    Open Browser    about:blank    ${BROWSER}
    Maximize Browser Window

Cerrar Navegador
    Close Browser

Abrir Sitio
    Go To    ${URL}
    Wait Until Element Is Visible    //a[normalize-space()='Iniciar LOGIN_DUMMY']    10s

Login Dummy Con RUN
    Click JS Resaltado    //a[normalize-space()='Iniciar LOGIN_DUMMY']
    
	Wait Until Element Is Visible    //input[@id='run_consultado']    15s
    Input Text    //input[@id='run_consultado']    ${RUN_CONSULTA}

	Wait Until Element Is Visible    //input[@id='usuario']    15s
    Input Text    //input[@id='usuario']    ${USUARIO_FINAL}

	Wait Until Element Is Visible    //input[@id='password']    10s
    Input Password    //input[@id='password']    ${PASSWORD_FINAL}
    Capture Page Screenshot
    Sleep    1s

    Wait Until Element Is Visible    //span[@class='button__text']    10s
    Click JS Resaltado    //span[@class='button__text']
    Capture Page Screenshot
    Sleep    1s
    Wait Until Page Contains Element    //nav    30s
    Capture Page Screenshot
	
	

Crear Registro RSH
    Wait Until Page Contains    Registro Social de Hogares    30s
    Click JS Resaltado          //a[contains(text(),'Crear')]
    Safe Click Confirmar

Completar Direccion
    Select From List By Label    //select[@id='irRegion']        METROPOLITANA DE SANTIAGO
    Select From List By Label    //select[@id='irComuna']        SAN MIGUEL
    Select From List By Label    //select[@id='irLocalidad']     SAN MIGUEL - SALESIANOS (CIUDAD)
    Select From List By Label    //select[@id='irCalle']         SALESIANOS
    Click JS Resaltado           //span[contains(text(),'SALESIANOS')]
    Input Text                  //input[@placeholder='Ej: 4321']    961
    Input Text                  //input[@id='irDepto']             301
    Select From List By Label    //select[@id='irTipoVivienda']     2. Departamento
    Input Text                  //input[@id='irReferencia']        Frente a Jumbo San Miguel
    Select From List By Label    //select[@id='irCompLuz']          ENEL
    Input Text                  //input[@id='irNroClienteLuz']     1111111
    Select From List By Label    //select[@id='irCompAgua']         Aguas Andinas S.A.
    Input Text                  //input[@id='irNroClienteAgua']    2222222
    Click JS Resaltado           //button[normalize-space()='Continuar']
    Sleep    3s

Completar Vivienda
    Wait Until Page Contains Element    //select[@id='domViv_v2']    30s
    Scroll Element Into View            //select[@id='domViv_v2']
    Select From List By Label           //select[@id='domViv_v2']    1. Propio pagado o pagándose
    Select From List By Label           //select[@id='domViv_v3']    1. Propia pagado o pagándose
    Select From List By Label           //select[@id='domViv_v4']    1. Red pública con medidor propio
    Select From List By Label           //select[@id='domViv_v5']    1. Con llave dentro de la vivienda
    Select From List By Label           //select[@id='domViv_v6']    1. WC, Conectado a alcantarillado
    Input Text                          //input[@id='domViv_v7']    2
    Select From List By Label           //select[@id='domViv_v8']    Sí
    Select From List By Label           //select[@id='domViv_v9']    1. Hormigón armado
    Select From List By Label           //select[@id='domViv_v9ec']    1. Bueno
    Select From List By Label           //select[@id='domViv_v10']    1. Parquet, madera, piso flotante o similar
    Select From List By Label           //select[@id='domViv_v10ec']    1. Bueno
    Select From List By Label           //select[@id='domViv_v11']    2. Losa hormigón
    Select From List By Label           //select[@id='domViv_v11ec']    1. Bueno
    Sleep    5s


Continuar Flujo
    Click JS Resaltado    //button[@id='continuar']
    Click JS Resaltado    //button[normalize-space()='OK']
    Sleep    3s


Completar Datos Pueblos Originarios
    ${existe}=    Run Keyword And Return Status
    ...    Page Should Contain Element    //select[contains(@id,'PuebloOriginario')]

    IF    ${existe}
        Select From List By Label
        ...    //select[contains(@id,'PuebloOriginario')]
        ...    0. No pertenece a ninguno de ellos
        Safe Click Continuar
    ELSE
        Log    Paso Pueblos Originarios no presente, se continúa flujo
    END

Completar Datos Educacionales
    ${existe}=    Run Keyword And Return Status
    ...    Page Should Contain Element    //select[contains(@id,'_e3')]

    IF    ${existe}
        Log    Formulario de Educación presente
        Click JS Resaltado    //label[contains(@for,'_e12')]
        Safe Click Continuar

        Select From List By Label    //select[contains(@id,'_e3')]    Decimo Año
        Select From List By Label    //select[contains(@id,'_e4')]    13. Educación Universitaria Completa
        Safe Click Continuar
    ELSE
        Log    Formulario de Educación NO presente, se continúa flujo
    END

Completar Formulario de Salud
    ${existe}=    Run Keyword And Return Status
    ...    Page Should Contain Element    //label[contains(@for,'_s12')]

    IF    ${existe}
        Log    Formulario de Salud presente
        Click JS Resaltado    //label[contains(@for,'_s12')]
        Safe Click Continuar

        Click JS Resaltado    //label[contains(@for,'_s2a1')]
        Click JS Resaltado    //label[contains(@for,'_s2b1')]
        Click JS Resaltado    //label[contains(@for,'_s2c1')]
        Click JS Resaltado    //label[contains(@for,'_s2d1')]
        Click JS Resaltado    //label[contains(@for,'_s2e1')]
        Safe Click Continuar
    ELSE
        Log    Formulario de Salud NO presente
    END

Cerrar Sesion
    Go To    ${URL_RSH}
    Wait Until Element Is Visible    //*[contains(normalize-space(),'Cerrar sesión')]    20s
    Click JS Resaltado               //*[contains(normalize-space(),'Cerrar sesión')]

Manejar Alert Expirado
    ${alert}=    Run Keyword And Return Status    Alert Should Be Present
    IF    ${alert}
        Accept Alert
    END

Click JS Resaltado
    [Arguments]    ${XPATH}
    Execute JavaScript
    ...    var el=document.evaluate("${XPATH}",document,null,XPathResult.FIRST_ORDERED_NODE_TYPE,null).singleNodeValue;
    ...    if(!el){throw "Elemento no encontrado: ${XPATH}";}
    ...    el.scrollIntoView({block:'center'});
    ...    el.style.outline='3px solid #e74c3c';
    ...    el.style.backgroundColor='rgba(231,76,60,0.15)';
    ...    el.click();
    ...    setTimeout(()=>{el.style.outline='';el.style.backgroundColor='';},300);

Safe Click Confirmar
    Wait Until Element Is Visible    //button[normalize-space()='confirmar']    30s
    Click JS Resaltado               //button[normalize-space()='confirmar']
