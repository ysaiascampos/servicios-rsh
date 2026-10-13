*** Settings ***
Documentation     Flujo Ventanilla Única - Solicitar credencial física cuidador
Library           SeleniumLibrary
Resource          ../RESOURCES/Resource_${ENV}.resource
Suite Setup       Abrir Navegador
Suite Teardown    Cerrar Navegador
Test Timeout      10 minutes


*** Variables ***
${RUN_CONSULTA}       10.472.698-4
${USUARIO_FINAL}   mmendoza
${PASSWORD_FINAL}  Ini2026mms



*** Test Cases ***
TC08_RSH_CIU_DESCARGA_CARTOLA
    Abrir Sitio
    Login Dummy
    Ir A Registro Social
    Descargar Cartola Hogar
    Cerrar Sesion

*** Keywords ***
Abrir Navegador
    Open Browser    about:blank    ${BROWSER}
    Maximize Browser Window

Cerrar Navegador
    Close All Browsers


Abrir Sitio
    Go To    ${URL}
    Wait Until Element Is Visible    //a[normalize-space()='Iniciar LOGIN_DUMMY']    6s



Login Dummy
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



Ir A Registro Social
    Unselect Frame
    Wait Until Page Does Not Contain    Cargando    30s
    Wait Until Page Contains Element    //a[contains(.,'Registro Social')]    30s
    Capture Page Screenshot

    Click JS Resaltado    //a[contains(.,'Registro Social')]
    Capture Page Screenshot


Descargar Cartola Hogar
    Wait Until Page Contains Element    //span[normalize-space()='assignment']    30s
    Capture Page Screenshot

    Click JS Resaltado    //span[normalize-space()='assignment']

    Wait Until Page Contains Element
    ...    //span[normalize-space()='Descargar mi cartola hogar']
    ...    30s
    Capture Page Screenshot

    Click JS Resaltado
    ...    //span[normalize-space()='Descargar mi cartola hogar']/ancestor::a[1]
    Sleep    10s


Cerrar Sesion
    Click JS Resaltado    //span[normalize-space()='Cerrar sesión']
    Capture Page Screenshot


Click JS Resaltado
    [Arguments]    ${XPATH}
    Execute JavaScript
    ...    var el=document.evaluate("${XPATH}",document,null,XPathResult.FIRST_ORDERED_NODE_TYPE,null).singleNodeValue;
    ...    if(!el){throw "Elemento no encontrado";}
    ...    el.scrollIntoView({block:'center'});
    ...    el.style.outline='3px solid red';
    ...    el.click();
