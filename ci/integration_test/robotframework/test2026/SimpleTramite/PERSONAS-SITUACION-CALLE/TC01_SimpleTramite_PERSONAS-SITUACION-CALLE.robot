*** Settings ***
Suite Setup       Abrir Navegador
Suite Teardown    Cerrar Navegador
Task Timeout      5 minutes
Library           SeleniumLibrary

*** Variables ***
${URL}            https://ventanillaunica.mds.cl/
${URL_RSH}        https://ventanillaunica.mds.cl/ciudadano
${BROWSER}        Chrome
${PASSWORD}       ventanilla
${RUN}            7527192-1

*** Test Cases ***
TC01_SimpleTramite_PERSONAS-SITUACION-CALLE QA
    [Documentation]    Flujo completo automatizado SimpleTramite PERSONAS-SITUACION-CALLE
    Abrir Sitio
    Login Dummy Con RUN
    Beneficios del Estado
    Beneficios Personas-Situacion-Calle
    Cerrar Sesión

*** Keywords ***
Abrir Navegador
    Open Browser    about:blank    ${BROWSER}
    Maximize Browser Window
    Set Selenium Speed    0.2s

Cerrar Navegador
    Close All Browsers

Abrir Sitio
    Go To    ${URL}
    Wait Until Page Contains Element
    ...    //a[normalize-space()='Iniciar LOGIN_DUMMY']
    ...    20s
    Capture Page Screenshot

Login Dummy Con RUN
    Safe Click JS    //a[normalize-space()='Iniciar LOGIN_DUMMY']
    Wait Until Page Contains Element    id=pass    30s
    Input Text    id=run    ${RUN}
    Input Text    id=pass    ${PASSWORD}
    Sleep    1s
    Safe Click JS    //span[@class='button__text']
    Wait Until Page Contains Element    //nav    30s
    Capture Page Screenshot

Beneficios del Estado
    Wait Until Page Contains Element
    ...    //a[normalize-space()='Ir a todos los beneficios del Estado']
    ...    20s
    Safe Click JS    //a[normalize-space()='Ir a todos los beneficios del Estado']
    Sleep    3s
    Press Keys    None    PAGE_DOWN
    Sleep    2s
    Capture Page Screenshot

Beneficios Personas-Situacion-Calle
    Safe Click JS    //a[normalize-space()='Personas en situación de calle']
    Sleep    3s
    Press Keys    None    PAGE_DOWN
    Sleep    2s
    Safe Click JS    //a[normalize-space()='Vivienda primero']
    Sleep    5s
    Press Keys    None    PAGE_DOWN
    Capture Page Screenshot
    Safe Click JS    //a[contains(@class,'boton-primario')]
	Sleep    5s
    Capture Page Screenshot
    Press Keys    None    PAGE_DOWN
    Sleep    2s
    Capture Page Screenshot	
    Sleep    2s
    Safe Click JS    //a[normalize-space()='Ir a la página']
    Sleep    5s
    Capture Page Screenshot
    Sleep    5s
    Press Keys    None    PAGE_DOWN
    Sleep    5s
    Capture Page Screenshot
    Press Keys    None    PAGE_DOWN
    Sleep    5s
	
	
	
	

Cerrar Sesión
    Press Keys    None    PAGE_UP
    Sleep    5s
    Press Keys    None    PAGE_DOWN
	Go To    ${URL_RSH}
    Press Keys    None    PAGE_DOWN
    Capture Page Screenshot
    Sleep    5s
    Safe Click JS    //*[contains(normalize-space(),'Cerrar sesión')]
    Capture Page Screenshot
    Sleep    5s
	Go To    ${URL}
    Capture Page Screenshot

Safe Click JS
    [Arguments]    ${XPATH}
    Execute JavaScript
    ...    var el = document.evaluate(
    ...    "${XPATH}",
    ...    document, null, XPathResult.FIRST_ORDERED_NODE_TYPE, null
    ...    ).singleNodeValue;
    ...    if (!el) {
    ...        console.log('Elemento no encontrado');
    ...        return;
    ...    }
    ...    el.style.display = 'block';
    ...    el.style.visibility = 'visible';
    ...    el.style.opacity = '1';
    ...    el.scrollIntoView({behavior:'instant', block:'center'});
 	...    el.style.border='3px solid #f1c40f';
    ...    setTimeout(function(){ el.click(); }, 500);



