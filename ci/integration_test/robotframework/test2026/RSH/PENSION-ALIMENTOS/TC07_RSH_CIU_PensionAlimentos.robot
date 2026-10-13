*** Settings ***
Library           SeleniumLibrary
Suite Setup       Abrir Navegador
Suite Teardown    Cerrar Navegador
Test Timeout      10 minutes
Resource          ../RESOURCES/Resource_${ENV}.resource


*** Variables ***
${BROWSER}         Chrome
${RUN_CONSULTA}    18.905.762-8
${USUARIO_FINAL}   mmendoza
${PASSWORD_FINAL}  Ini2026mms


*** Test Cases ***
TC07_RSH_CIU_PensionAlimentos
    [Documentation]    Orquestador flujo completo Pensión de Alimentos (Click Resaltado)
    Abrir Sitio
    Login Dummy
    Ir A Registro Social
	Ir A Otros Datos
    Ir A Pension De Alimentos
    Ingresar Tramite Pension
    #     Completar Formulario
	Volver
    Cerrar Sesion

*** Keywords ***
Abrir Navegador
    Open Browser    about:blank    ${BROWSER}
    Maximize Browser Window
    Set Selenium Timeout    12s


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

Ir A Otros Datos
    Click JS Resaltado    //span[normalize-space()='Otros datos']
	Capture Page Screenshot
    Wait Until Page Does Not Contain    Cargando    10s
    Sleep    10s    



Ir A Pension De Alimentos
    Wait Until Element Is Visible    //span[normalize-space()='group_work']    10s
    Sleep    10s    
	Click JS Resaltado    //span[normalize-space()='group_work']
    Capture Page Screenshot

	
    Sleep    10s    
    Scroll Down

    Wait Until Element Is Visible    //button[normalize-space()='Pensión de alimentos']    10s
    Click JS Resaltado    //button[normalize-space()='Pensión de alimentos']
	
	Capture Page Screenshot
    Sleep    5s    

    Wait Until Element Is Visible
    ...    //div[@id='panelsStayOpen-collapse-4']//a[normalize-space()='Ir a los trámites']
    ...    15s
    Click JS Resaltado
    ...    //div[@id='panelsStayOpen-collapse-4']//a[normalize-space()='Ir a los trámites']
	Capture Page Screenshot
    Sleep    5s    

Ingresar Tramite Pension
    Scroll Down
    Wait Until Element Is Visible    //i[contains(@class,'fa-hands-helping')]    15s
    Sleep    10s    

    Click JS Resaltado    //i[contains(@class,'fa-hands-helping')]
	Capture Page Screenshot
	
	Sleep    10s
	
    Wait Until Element Is Visible
    ...    //button[contains(@onclick,'complemento-alimento')]
    ...    15s

    Scroll Down
    Sleep    5s    
    Scroll Up

    Click JS Resaltado
    ...    //button[contains(@onclick,'complemento-alimento')]
	Sleep    10s


Completar Formulario
    Scroll Down
    Wait Until Element Is Visible    //select[@id='beneficiario']    15s
    Select From List By Label
    ...    //select[@id='beneficiario']
    ...    Benjamín Jesús Inostroza (11 años)
    Sleep    5s


    Scroll Down
    Select From List By Label
    ...    //select[@id='receptor']
    ...    Alicia Cristina Gómez (31 años)
	
	
	Sleep    5s    # QA visual / animación lenta

    Input Text    //input[@id='monto-pension']    50.000
    Sleep    10s
	
	
	

Volver
    Click JS Resaltado    //span[normalize-space()='Volver']
	Capture Page Screenshot

	Sleep    10s    
    Wait Until Page Does Not Contain    Cargando    10s	

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

