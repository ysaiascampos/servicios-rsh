*** Settings ***
Documentation     Flujo Ventanilla Única - Solicitar credencial física cuidador
Library           SeleniumLibrary
Resource          ../RESOURCES/Resource_${ENV}.resource
Suite Setup       Abrir Navegador
Suite Teardown    Cerrar Navegador
Test Timeout      10 minutes


*** Variables ***
${RUN_CONSULTA}    9.072.183-6
${USUARIO_FINAL}   mmendoza
${PASSWORD_FINAL}  Ini2026mms


*** Test Cases ***
TC06_RSH_CUI_Registro_Cuidador_ARC
    [Documentation]    Flujo completo desde login hasta solicitud de credencial física
    Abrir Sitio
    Login Dummy
    Ir A Registro Social
    Solicitar Credencial Fisica
    Enviar Solicitud
    Volver Al Inicio
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
    Wait Until Page Contains Element    //a[contains(.,'Registro Social')]    20s
    Capture Page Screenshot
	
    Click JS Resaltado    //a[contains(.,'Registro Social')]
    Capture Page Screenshot


Solicitar Credencial Fisica
    Wait Until Element Is Visible
    ...    //h6[normalize-space()='Solicitar credencial física']
    Scroll Element Into View
    ...    //h6[normalize-space()='Solicitar credencial física']
    Click JS Resaltado
    ...    //h6[normalize-space()='Solicitar credencial física']
    Sleep    5


Enviar Solicitud
    Wait Until Element Is Visible
    ...    //button[normalize-space()='Siguiente']
    Click Button
    ...    //button[normalize-space()='Siguiente']
    Sleep    5
	Scroll Down
    Sleep    5
	Scroll Down
    Sleep    5
	

Volver Al Inicio
    Wait Until Element Is Visible    //a[normalize-space()='Volver']
    Click Element    //a[normalize-space()='Volver']


Cerrar Sesion
    Go To    ${URL_RSH}
	Sleep    10s    
    Wait Until Element Is Visible
    ...    //*[contains(normalize-space(),'Cerrar sesión')]
    ...    15s
    Click JS Resaltado
    ...    //*[contains(normalize-space(),'Cerrar sesión')]
	Capture Page Screenshot
    Go To    ${URL}
	Capture Page Screenshot
	
	

Manejar Alert Expirado
    ${alert}=    Run Keyword And Return Status    Alert Should Be Present
    IF    ${alert}
        Accept Alert
    END

Click JS Resaltado
    [Arguments]    ${XPATH}
    Execute JavaScript
    ...    var el=document.evaluate("${XPATH}",document,null,XPathResult.FIRST_ORDERED_NODE_TYPE,null).singleNodeValue;
    ...    if(!el){throw "Elemento no encontrado";}
    ...    el.scrollIntoView({block:'center'});
    ...    el.style.outline='3px solid red';
    ...    el.click();
	

Scroll Down
    Execute JavaScript    window.scrollBy(0,800)

Scroll Up
    Execute JavaScript    window.scrollTo(0,0)
	
	
