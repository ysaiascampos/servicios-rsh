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
TC01_SimpleTramite_BONOS-SUBSIDIOS QA
    [Documentation]    Flujo completo automatizado SimpleTramite BONOS-SUBSIDIOS
    Abrir Sitio
    Login Dummy Con RUN
    Beneficios del Estado
    Beneficios Bonos-Subsidios
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
    ...    30s
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
    ...    30s
    Safe Click JS    //a[normalize-space()='Ir a todos los beneficios del Estado']
    Sleep    3s
    Press Keys    None    PAGE_DOWN
    Sleep    2s
    Capture Page Screenshot

Beneficios Bonos-Subsidios
    Safe Click JS    //a[normalize-space()='Bonos y subsidios']
    Sleep    3s
    Press Keys    None    PAGE_DOWN
    Sleep    2s
    Safe Click JS    //a[normalize-space()='Subsidio Clase Media para Compra de Viviendas (DS1)']
    Sleep    5s
    Press Keys    None    PAGE_DOWN
    Capture Page Screenshot
    Safe Click JS    //a[normalize-space()='Postular']
    Sleep    5s
    Capture Page Screenshot
	Sleep    10s
    Capture Page Screenshot
    Sleep    3s
    Press Keys    None    PAGE_DOWN	
    Sleep    3s
	Safe Click JS    //a[normalize-space()='Volver']

	
	

Cerrar Sesión
    Press Keys    None    PAGE_UP
    Sleep    5s
    Press Keys    None    PAGE_DOWN
    Capture Page Screenshot
    Sleep    5s
	Go To    ${URL_RSH}
    Press Keys    None    PAGE_DOWN
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
    ...    el.style.border='3px solid #1e90ff';
    ...    el.style.boxShadow='0 0 15px #1e90ff';
    ...    el.style.backgroundColor='#e6f2ff';
    ...    setTimeout(function(){ el.click(); }, 500);



