*** Settings ***
Library           SeleniumLibrary
Library           OperatingSystem
Resource          ResourceREC.robot

*** Variables ***

*** Test Cases ***
TC06_RSH_REC_RectificaCotizacionSalud
    [Setup]    Run Keywords    Open Browser    ${URL}    ${BROWSER}
    ...    AND    Set Selenium Speed    ${SELSPEED}
    Set Global Variable    ${UPLOAD_DUMMY_DOCX_FILE}    ${CURDIR}/doc_prueba.pdf
    # open    ${URL}
    Maximize Browser Window
    Sleep    2s
    captureEntirePageScreenshot
    click    xpath=//*[@id="login-username"]
    type    xpath=//*[@id="login-username"]    ${USER}
    Sleep    1s
    click    xpath=//*[@id="login-password"]
    type    xpath=//*[@id="login-password"]    ${PASSWORD}
    Sleep    1s
    captureEntirePageScreenshot
    click    //*[@id="loginform"]/div[3]/div/button
    Sleep    2s
    captureEntirePageScreenshot
    #---------------------------------------------------------------
    click    xpath=//*[@id="bs-example-navbar-collapse-1"]/ul[1]/li[2]/a
    Mouse Over    xpath=//*[@id="bs-example-navbar-collapse-1"]/ul[1]/li[2]/ul/li[2]/a
    Sleep    2s
    captureEntirePageScreenshot
    click    xpath=//*[@id="bs-example-navbar-collapse-1"]/ul[1]/li[2]/ul/li[2]/a
    Sleep    3s
    click    xpath=//*[@id="rut_usuario"]
    type    xpath=//*[@id="rut_usuario"]    ${RUTCIUDADANO4}
    Sleep    1s
    click    xpath=//*[@id="dv"]
    type    xpath=//*[@id="dv"]    ${DVCIUDADANO4}
    captureEntirePageScreenshot
    click    xpath=//*[@id="btn_consultar"]
    Sleep    3s
    #---------------------------------------------------------------
    #---Seleccion de tramite
    captureEntirePageScreenshot
    sendKeys    xpath=//*[@id="content"]/div[3]/div/h1    PAGE_DOWN
    Sleep    1s
    click    xpath=//*[@id="ir_tra_op4"]
    Sleep    1s
    captureEntirePageScreenshot
    click    xpath=//*[@id="content"]/form[2]/div/div/div/div[6]/div/button
    Sleep    3s
    #---------------------------------------------------------------
    #---Seleccion de Integrante
    captureEntirePageScreenshot
    sendKeys    xpath=//html/body/div/div/section/div[3]/div/h3    PAGE_DOWN
    Sleep    1s
    click    xpath=//*[@id="12639494-2"]
    Sleep    1s
    captureEntirePageScreenshot
    click    xpath=//*[@id="btnfin"]
    Sleep    6s
    #---------------------------------------------------------------
    #---Seleccion Cotizacion de Salud
    captureEntirePageScreenshot
    sendKeys    xpath=//html/body/div[1]/div/section/div[3]/div/h3    PAGE_DOWN
    Sleep    1s
    click    xpath=//*[@id="ir_rec_op4"]
    captureEntirePageScreenshot
    click    xpath=//*[@id="content"]/form[2]/div[2]/div/button[2]
    Sleep    6s
    #---------------------------------------------------------------
    #---Confirmacion de Datos del Solicitando
    #captureEntirePageScreenshot
    sendKeys    xpath=//html/body/div/div/section/div[3]/div/h3    PAGE_DOWN
    Sleep    1s
    captureEntirePageScreenshot
    click    xpath=//*[@id="content"]/div[4]/div/form/div[2]/div/button[2]
    Sleep    6s
    #---------------------------------------------------------------
    #---Razon de Rectificacióo de cotizaciones de salud
    captureEntirePageScreenshot
    sendKeys    xpath=//html/body/div[1]/div/section/div[3]/div/h3    PAGE_DOWN
    Sleep    1s
    click    xpath=//html/body/div[1]/div/section/form[2]/div/div/div[1]/div[2]/div/div[1]/div[3]/label/input
    Sleep    1s
    click    xpath=//*[@id="monto1"]
    type    xpath=//*[@id="monto1"]    65000
    Sleep    1s
    click    xpath=//*[@id="fecha1"]
    click    xpath=//*[@id="ui-datepicker-div"]/table/tbody/tr[1]/td[1]/a
    captureEntirePageScreenshot
    click    xpath=//*[@id="btnContinuar"]
    Sleep    3s
    #---------------------------------------------------------------
    #---Regresa a Pantalla Anterior y Despues a Bandeja de Solicitudes
    captureEntirePageScreenshot
    click    xpath=//*[@id="bs-example-navbar-collapse-1"]/ul[1]/li[1]/a
    Sleep    3s
    captureEntirePageScreenshot
    sendKeys    xpath=//html/body/div/div[1]/section/div[4]/div[2]/div/div/div[1]/strong    PAGE_DOWN
    Sleep    1s
    click    xpath=//*[@id="run"]
    type    xpath=//*[@id="run"]    ${RUTCIUDADANO4}
    Sleep    1s
    click    xpath=//*[@id="mando"]/div[2]/div/div/div[2]/form/div[1]/div/div[5]/button
    Sleep    1s
    click    xpath=//*[@id="id_tipo_solicitud"]
    #click    xpath=//*[@id="id_tipo_solicitud"]/option[contains(text(),"de Cotizaciones de")]
    Select From List By Value    xpath=//*[@id="id_tipo_solicitud"]    73
    Sleep    1s
    click    xpath=//*[@id="id_estado"]
    click    xpath=//*[@id="id_estado"]/option[contains(text(),"Pendiente Entregar Documentos")]
    Sleep    1s
    click    xpath=//*[@id="mando"]/div[2]/div/div/div[2]/form/div[1]/div/div[6]/button
    Sleep    6s
    sendKeys    xpath=//html/body/div/div[1]/section/div[4]/div[2]/div/div/div[1]/strong    PAGE_DOWN
    Sleep    1s
    click    xpath=//*[@id="mando"]/div[2]/div/div/div[2]/form/div[1]/div/div[5]/button
    captureEntirePageScreenshot
    click    xpath=//*[@id="dropdownOpciones"]/button
    Sleep    1s
    captureEntirePageScreenshot
    click    link=Rectificación de Cotizaciones de Salud
    Sleep    3s
    #---------------------------------------------------------------
    #---Gestion de la Solicitud
    captureEntirePageScreenshot
    sendKeys    xpath=//html/body/div/div[1]/section/div[2]/form[2]/div/div[3]/div[1]    PAGE_DOWN
    Sleep    1s
    captureEntirePageScreenshot
    sendKeys    xpath=//html/body/div/div[1]/section/div[2]/form[2]/div/div[4]/div[3]/div[2]/form/u/h3    PAGE_DOWN
    Sleep    1s
    captureEntirePageScreenshot
    #---Upload Documento 1
    click    xpath=//html/body/div/div[1]/section/div[2]/form[2]/div/div[4]/div[3]/div[2]/form/div[1]/label/input
    Sleep    1s
    Choose File    xpath=//*[@id="frmup"]/div[4]/div/div[1]/input    ${UPLOAD_DUMMY_DOCX_FILE}
    Sleep    1s
    click    xpath=//*[@id="frmup"]/div[4]/div/div[2]/input
    Sleep    1s
    captureEntirePageScreenshot
    #---Upload Documento 2
    click    xpath=//html/body/div/div[1]/section/div[2]/form[2]/div/div[4]/div[3]/div[2]/form/div[2]/label/input
    Sleep    1s
    Choose File    xpath=//*[@id="frmup"]/div[4]/div/div[1]/input    ${UPLOAD_DUMMY_DOCX_FILE}
    Sleep    1s
    click    xpath=//*[@id="frmup"]/div[4]/div/div[2]/input
    Sleep    1s
    captureEntirePageScreenshot
    #---Upload Documento 3
    click    xpath=//html/body/div/div[1]/section/div[2]/form[2]/div/div[4]/div[3]/div[2]/form/div[3]/label/input
    Sleep    1s
    Choose File    xpath=//*[@id="frmup"]/div[4]/div/div[1]/input    ${UPLOAD_DUMMY_DOCX_FILE}
    Sleep    1s
    click    xpath=//*[@id="frmup"]/div[4]/div/div[2]/input
    Sleep    1s
    captureEntirePageScreenshot
    #---Boton Bandeja Entrada
    click    xpath=//*[@id="vistaId"]/div/div[4]/div[4]/button[1]
    Sleep    6s
    #---------------------------------------------------------------
    #---Busca el Registro para Rechazarlo
    captureEntirePageScreenshot
    sendKeys    xpath=//html/body/div/div[1]/section/div[4]/div[2]/div/div/div[1]/strong    PAGE_DOWN
    Sleep    1s
    click    xpath=//*[@id="run"]
    type    xpath=//*[@id="run"]    ${RUTCIUDADANO4}
    Sleep    1s
    click    xpath=//*[@id="mando"]/div[2]/div/div/div[2]/form/div[1]/div/div[5]/button
    Sleep    1s
    click    xpath=//*[@id="id_tipo_solicitud"]
    Select From List By Value    xpath=//*[@id="id_tipo_solicitud"]    73
    Sleep    1s
    click    xpath=//*[@id="id_estado"]
    click    xpath=//*[@id="id_estado"]/option[contains(text(),"Pendiente Entregar Documentos")]
    Sleep    1s
    click    xpath=//*[@id="mando"]/div[2]/div/div/div[2]/form/div[1]/div/div[6]/button
    Sleep    6s
    sendKeys    xpath=//html/body/div/div[1]/section/div[4]/div[2]/div/div/div[1]/strong    PAGE_DOWN
    Sleep    1s
    click    xpath=//*[@id="mando"]/div[2]/div/div/div[2]/form/div[1]/div/div[5]/button
    captureEntirePageScreenshot
    click    xpath=//*[@id="dropdownOpciones"]/button
    Sleep    1s
    captureEntirePageScreenshot
    captureEntirePageScreenshot
    click    link=Rechazar Solicitud
    Sleep    3s
    captureEntirePageScreenshot
    Sleep    3s
    click    xpath=//html/body/div[1]/div[2]/div/div/div[2]/form/div[2]/div/div[1]/div/textarea
    type    xpath=//html/body/div[1]/div[2]/div/div/div[2]/form/div[2]/div/div[1]/div/textarea    PRUEBA ROBOTFRAMEWORK
    Sleep    1s
    captureEntirePageScreenshot
    click    xpath=//html/body/div[1]/div[2]/div/div/div[2]/form/div[2]/div/div[2]/button
    Sleep    3s
    captureEntirePageScreenshot
    Sleep    1s
    #---------------------------------------------------------------
    click    link=Cerrar sesión
    Sleep    1s
    captureEntirePageScreenshot
    close
    [Teardown]    Close Browser
