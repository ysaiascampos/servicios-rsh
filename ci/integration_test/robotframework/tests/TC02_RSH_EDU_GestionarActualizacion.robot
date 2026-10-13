*** Settings ***
Library           SeleniumLibrary
Library           OperatingSystem
Resource          ResourceM2_EDU.robot

*** Variables ***

*** Test Cases ***
TC02_RSH_GestionarActualizacion
    [Setup]    Run Keywords    Open Browser    ${URL}    ${BROWSER}
    ...    AND    Set Selenium Speed    ${SELSPEED}
    Set Global Variable    ${UPLOAD_DUMMY_DOCX_FILE}    ${CURDIR}/doc_prueba.pdf
    #----------------------------Login RSH------------------------------
    # open    ${URL}
    Maximize Browser Window
    click    xpath=//*[@id="login-username"]
    type    xpath=//*[@id="login-username"]    ${USER}
    Sleep    1s
    click    xpath=//*[@id="login-password"]
    type    xpath=//*[@id="login-password"]    ${PASSWORDS}
    Sleep    1s
    captureEntirePageScreenshot
    click    //*[@id="loginform"]/div[3]/div/button
    Sleep    2s
    captureEntirePageScreenshot
    #-------------------------------------------------------------------
    sendKeys    xpath=//*[@id="mando"]/div[2]    PAGE_DOWN
    sleep    2s
    click    xpath=//*[@id="mando"]/div[2]/div/div/div[2]/form/div[1]/div/div[5]/button
    Sleep    1s
    click    xpath=//*[@id="id_tipo_solicitud"]
    click    xpath=//*[@id="id_tipo_solicitud"]/option[contains(text(),"Actualización de Módulo Educación")]
    Sleep    1s
    click    xpath=//*[@id="id_estado"]
    click    xpath=//*[@id="id_estado"]/option[contains(text(),"Pendiente Entregar Documentos")]
    Sleep    1s
    click    xpath=//*[@id="mando"]/div[2]/div/div/div[2]/form/div[1]/div/div[6]/button
    Sleep    8s
    sendKeys    xpath=//*[@id="mando"]/div[2]    PAGE_DOWN
    Sleep    1s
    click    xpath=//*[@id="dropdownOpciones"]/button
    sleep    3s
    click    link=Gestionar Solicitud
    sleep    5s
    #------prueba
    ${USERinc}=    Get Text    //*[@id="encp1"]/div[2]/div[1]/div/div[1]/p
    click    //*[@id="e1"]
    Sleep    5s
    Click    //*[@id="e1"]/option[2]
    Click    //*[@id="e2"]
    Click    //*[@id="e2"]/option[5]
    Click    //*[@id="e4"]
    Click    //*[@id="e4"]/option[12]
    Click    //*[@id="e3"]
    click    //*[@id="e3"]/option[6]
    captureEntirePageScreenshot
    #-----------
    sendKeys    xpath=//*[@id="content"]/footer/div/div[3]    PAGE_DOWN
    Sleep    1s
    #---Documento formulario soli
    click    xpath=//html/body/div/div/section/div[2]/form/div/div[5]/div[2]/form/div[1]/div[1]/label/input
    sleep    3s
    Choose File    xpath=//*[@id="frmup"]/div[2]/div/div[1]/input    ${UPLOAD_DUMMY_DOCX_FILE}
    sleep    3s
    click    xpath=//input[@value='Subir Archivo']
    sleep    5s
    #---Documento formulario soli
    click    xpath=//*[@id="frmup"]/div[1]/div[2]/label/input
    sleep    3s
    Choose File    xpath=//*[@id="frmup"]/div[2]/div/div[1]/input    ${UPLOAD_DUMMY_DOCX_FILE}
    sleep    3s
    click    xpath=//input[@value='Subir Archivo']
    sleep    5s
    click    //*[@id="vistaId"]/div/div[6]/button[2]
    sleep    6s
    #----------------------------Cerrar Sesión RSH------------------------------
    close
    [Teardown]    Close Browser
