*** Settings ***
Library           SeleniumLibrary
Suite Setup       Abrir Navegador
Suite Teardown    Cerrar Navegador
Test Timeout      6 minutes
Resource          ../RESOURCES/Resource_${ENV}.resource


*** Variables ***
${RUN_CONSULTA}    17.706.423-8
${USUARIO_FINAL}   mmendoza
${PASSWORD_FINAL}  Ini2026mms



*** Test Cases ***
TC09_RSH_CIU_CUIDADOS_DESCARGA_CREDENCIAL
    Abrir Sitio
    Login Dummy
    Ir A Registro Social
    Ir A Cuidados
    Descargar Credencial Cuidadora
    Cerrar Sesion

*** Keywords ***
Abrir Navegador
    Open Browser    about:blank    ${BROWSER}
    Maximize Browser Window
    Set Selenium Timeout    30s
    Set Selenium Implicit Wait    0s

Cerrar Navegador
    Close All Browsers

Abrir Sitio
    Go To    ${URL}
    Wait Until Page Contains Element    //a[normalize-space()='Iniciar LOGIN_DUMMY']    30s
    Capture Page Screenshot

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
    Click JS Resaltado    //a[contains(.,'Registro Social')]
    Capture Page Screenshot

Ir A Cuidados
    Wait Until Page Does Not Contain    Cargando    40s
    Wait Until Page Contains Element    //h5[normalize-space()='Cuidados']    40s
    Click JS Resaltado    //h5[normalize-space()='Cuidados']
    Capture Page Screenshot

Descargar Credencial Cuidadora
    Wait Until Page Contains Element
    ...    //h6[normalize-space()='Descargar credencial de persona cuidadora']
    ...    20s

    Click JS Resaltado
    ...    //h6[normalize-space()='Descargar credencial de persona cuidadora']/ancestor::a[1]

    Capture Page Screenshot

    Wait Until Page Contains Element
    ...    //*[normalize-space()='Aceptar' and (self::button or self::span or self::div)]
    ...    30s

    Click JS Resaltado
    ...    //*[normalize-space()='Aceptar' and (self::button or self::span or self::div)]

    Capture Page Screenshot

Cerrar Sesion
    Press Keys    None    PAGE_UP
    Sleep    5s
    Press Keys    None    PAGE_DOWN
	Go To    ${URL_RSH}
    Press Keys    None    PAGE_DOWN
    Capture Page Screenshot
    Sleep    5s
    Click JS Resaltado    //*[contains(normalize-space(),'Cerrar sesión')]
    Capture Page Screenshot
    Sleep    5s
	Go To    ${URL}
    Capture Page Screenshot
	
	
Click JS Resaltado
    [Arguments]    ${XPATH}
    Execute JavaScript
    ...    var el=document.evaluate("${XPATH}",document,null,XPathResult.FIRST_ORDERED_NODE_TYPE,null).singleNodeValue;
    ...    if(!el){throw "Elemento no encontrado";}
    ...    el.scrollIntoView({block:'center'});
    ...    el.style.outline='3px solid red';
    ...    el.click();
	