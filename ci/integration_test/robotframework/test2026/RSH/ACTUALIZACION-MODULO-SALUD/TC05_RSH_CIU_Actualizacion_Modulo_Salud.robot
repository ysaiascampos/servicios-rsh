*** Settings ***
Documentation     TC05 RSH CIU - Actualización Módulo Salud (FINAL)
Suite Setup       Abrir Navegador
Suite Teardown    Cerrar Navegador
Test Timeout      5 minutes
Library           SeleniumLibrary
Resource    ../RESOURCES/Resource_${ENV}.resource

*** Variables ***
${RUN_CONSULTA}    26226599-4
${USUARIO_FINAL}   mmendoza
${PASSWORD_FINAL}  Ini2026mms


*** Test Cases ***
TC05_RSH_CIU_Actualizacion_Modulo_Salud
    Abrir Sitio
    Login Dummy
    Ir A Registro Social
    Ir A Integrantes
    Acceder Modulo Salud
	Actualizar Datos Salud
    Responder Preguntas Salud
    Volver A Integrantes
    Cerrar Sesion

*** Keywords ***
Abrir Navegador
    Open Browser    about:blank    ${BROWSER}
    Maximize Browser Window
    Set Selenium Speed    0.1s

Cerrar Navegador
    Close All Browsers

Abrir Sitio
    Go To    ${URL}
    Wait Until Element Is Visible
    ...    //a[normalize-space()='Iniciar LOGIN_DUMMY']
    ...    10s

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
    Wait Until Page Does Not Contain    Cargando    20s
    Wait Until Page Contains Element    //a[contains(.,'Registro Social')]    20s
    Capture Page Screenshot

    Click JS Resaltado    //a[contains(.,'Registro Social')]
    Capture Page Screenshot




Ir A Integrantes
    Wait Until Element Is Visible
    ...    //span[normalize-space()='Integrantes']
    ...    10s
    Click JS Resaltado    //span[normalize-space()='Integrantes']
    Wait Until Page Does Not Contain    Cargando    15s

Acceder Modulo Salud
    Wait Until Element Is Visible
    ...    (//a[.//h6[normalize-space()='Actualizar']])[3]
    ...    15s
    Click JS Resaltado    (//a[.//h6[normalize-space()='Actualizar']])[3]

    Wait Until Element Is Visible
    ...    //span[normalize-space()='Salud']
    ...    10s
    Click JS Resaltado    //span[normalize-space()='Salud']
    
	Wait Until Page Does Not Contain    Cargando    15s


Actualizar Datos Salud
    Wait Until Element Is Visible
    ...    //a[contains(normalize-space(.),'Actualizar datos de salud')]
    ...    10s

    Click JS Resaltado    //a[contains(normalize-space(.),'Actualizar datos de salud')]
    Sleep    3s
	
Responder Preguntas Salud
    Click JS Resaltado    (//label[contains(.,'No')])
    Sleep    3s
    Click JS Resaltado    (//label[contains(.,'Sí')])[1]
    Sleep    2s
    Click JS Resaltado    (//label[contains(.,'Sí')])[2]
    Sleep    2s
    Click JS Resaltado    (//label[contains(.,'Sí')])[3]
    Sleep    2s
    Click JS Resaltado    (//label[contains(.,'Sí')])[4]
    Sleep    2s
    Click JS Resaltado    (//label[contains(.,'Sí')])[5]
    Sleep    2s
    Click JS Resaltado    (//label[contains(.,'Sí')])[6]
    Sleep    3s


Volver A Integrantes
    Wait Until Element Is Visible
    ...    //span[normalize-space()='Volver']
    ...    10s
    Click JS Resaltado    //span[normalize-space()='Volver']
    Wait Until Page Does Not Contain    Cargando    10s

Cerrar Sesion
    Go To    ${URL_RSH}
    Click JS Resaltado    //*[contains(normalize-space(),'Cerrar sesión')]
    Go To    ${URL}	
	

	
Click JS Resaltado
    [Arguments]    ${XPATH}
    Execute JavaScript
    ...    var el=document.evaluate("${XPATH}",document,null,XPathResult.FIRST_ORDERED_NODE_TYPE,null).singleNodeValue;
    ...    if(!el){throw "Elemento no encontrado";}
    ...    el.scrollIntoView({block:'center'});
    ...    el.style.outline='3px solid red';
    ...    el.click();