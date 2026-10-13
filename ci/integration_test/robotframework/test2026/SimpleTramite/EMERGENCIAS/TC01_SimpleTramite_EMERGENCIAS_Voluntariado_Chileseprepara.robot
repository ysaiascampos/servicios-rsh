*** Settings ***
Suite Setup       Abrir Navegador
Suite Teardown    Cerrar Navegador
Task Timeout      5 minutes
Library           SeleniumLibrary
Resource    ../RESOURCES/Resource_${ENV}.resource

*** Variables ***
${RUN_CONSULTA}    10.472.698-4
${USUARIO_FINAL}    mmendoza
${PASSWORD_FINAL}   Ini2026mms


*** Test Cases ***
TC01_SimpleTramite_EMERGENCIAS_Voluntariado_Chileseprepara

    [Documentation]    Flujo completo automatizado SimpleTramite EMERGENCIAS
    Abrir Sitio
    Login Dummy Con RUN
    Beneficios del Estado
    Beneficios Emergencias
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


Beneficios del Estado
    Wait Until Page Contains Element
    ...    //a[normalize-space()='Ir a todos los beneficios del Estado']
    ...    20s
    Click JS Resaltado    //a[normalize-space()='Ir a todos los beneficios del Estado']
    Sleep    3s
    Press Keys    None    PAGE_DOWN
    Sleep    2s
    Capture Page Screenshot


Beneficios Emergencias
    Click JS Resaltado    //a[normalize-space()='Emergencias']
    Sleep    3s
    Press Keys    None    PAGE_DOWN
    Sleep    2s
	Click JS Resaltado    //a[contains(normalize-space(), 'Voluntariado #Chileseprepara')]

    Sleep    5s
    Press Keys    None    PAGE_DOWN
    Capture Page Screenshot
    Click JS Resaltado    //a[normalize-space()='Postular']
    Sleep    5s
    Capture Page Screenshot
	Sleep    10s
    Capture Page Screenshot
    Sleep    3s
    Press Keys    None    PAGE_DOWN	
    Sleep    3s
	#  Click JS Resaltado    //a[normalize-space()='Volver']

	
	

Cerrar Sesión
    Press Keys    None    PAGE_UP
    Sleep    5s
    Press Keys    None    PAGE_DOWN
    Capture Page Screenshot
    Sleep    5s
	Go To    ${URL_RSH}
    Press Keys    None    PAGE_DOWN
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





