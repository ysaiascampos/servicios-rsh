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
TC01_SimpleTramite_CONADI_FDI_Subsidio_construccion_de_obras_de_riego_y_drenaje_para_indigenas

    [Documentation]    Flujo completo automatizado SimpleTramite CONADI
    Abrir Sitio
    Login Dummy Con RUN
    Beneficios del Estado
    Beneficios Comunidad Indigena
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
    ...    30s
    Click JS Resaltado    //a[normalize-space()='Ir a todos los beneficios del Estado']
    Sleep    3s
    Press Keys    None    PAGE_DOWN
    Sleep    2s
    Capture Page Screenshot

Beneficios Comunidad Indigena
    Click JS Resaltado    //a[normalize-space()='Comunidades Indígenas']
    Sleep    3s
    Press Keys    None    PAGE_DOWN
    Sleep    2s
    Click JS Resaltado    //a[normalize-space()='Fondo de Tierras y Aguas Indígenas - Subsidio a la construcción de obras de riego y drenaje para indígenas']
    Sleep    5s
    Press Keys    None    PAGE_DOWN
    Capture Page Screenshot
    Sleep    5s
    Press Keys    None    PAGE_DOWN
    Capture Page Screenshot
	
	
	${postular}=    Run Keyword And Return Status
    ...    Wait Until Element Is Visible
    ...    //*[normalize-space()='Postular']
    ...    20s

    IF    ${postular}
        Click JS Resaltado    //*[normalize-space()='Postular']
        Sleep    5s
        Capture Page Screenshot
        Ir A Pagina Externa
    ELSE
        Log    FDI - Subsidio a la construcción de obras de riego y drenaje para indígenas: No disponible para postulación    WARN
        Capture Page Screenshot
    END
    Sleep    5s




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


Ir A Pagina Externa
    Wait Until Page Contains Element    //a[contains(.,'Ir a la página')]    40s
    Execute JavaScript
    ...    var el=document.evaluate(
    ...    "//a[contains(.,'Ir a la página')]",
    ...    document,null,XPathResult.FIRST_ORDERED_NODE_TYPE,null
    ...    ).singleNodeValue;
    ...    el.scrollIntoView({block:'center'});
    ...    el.style.outline='3px solid red';
    ...    el.click();
	
	
   #  Wait Until Number Of Windows Is    2    20s
   #  Switch Window    NEW



Click JS Resaltado
    [Arguments]    ${XPATH}
    Execute JavaScript
    ...    var el=document.evaluate("${XPATH}",document,null,XPathResult.FIRST_ORDERED_NODE_TYPE,null).singleNodeValue;
    ...    if(!el){throw "Elemento no encontrado";}
    ...    el.scrollIntoView({block:'center'});
    ...    el.style.outline='3px solid red';
    ...    el.click();
	
	
Limpiar Cache Navegador
    Log    Limpiando cache del navegador    INFO
    Delete All Cookies
    Execute JavaScript    window.localStorage.clear();
    Execute JavaScript    window.sessionStorage.clear();	
