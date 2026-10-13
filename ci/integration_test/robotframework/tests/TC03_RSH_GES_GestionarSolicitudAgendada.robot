*** Settings ***
Library           SeleniumLibrary
Resource          ResourceGES.robot

*** Variables ***

*** Test Cases ***
TC03_RSH_GES_GestionarSolicitudAgendada
    [Setup]    Run Keywords    Open Browser    ${URL}    ${BROWSER}
    ...    AND    Set Selenium Speed    ${SELSPEED}
    # open    ${URL}
    Maximize Browser Window
    click    xpath=//*[@id="login-username"]
    type    xpath=//*[@id="login-username"]    ${USER}
    Sleep    1s
    click    xpath=//*[@id="login-password"]
    type    xpath=//*[@id="login-password"]    ${PASSWORD}
    Sleep    1s
    captureEntirePageScreenshot
    click    //*[@id="loginform"]/div[3]/div/button
    Sleep    7s
    captureEntirePageScreenshot
    #---------------------------------------------------------------
    #---Filtros
    sendKeys    //*[@id="mando"]/div[2]/div/div/div[1]/strong    PAGE_DOWN
    captureEntirePageScreenshot
    click    xpath=//*[@id="mando"]/div[2]/div/div/div[2]/form/div[1]/div/div[5]/button
    Sleep    2s
    click    xpath=//*[@id="id_estado"]
    Sleep    1s
    click    //option[contains(text(),"Agendado")]
    Sleep    1s
    #---Busqueda
    Mouse Over    xpath=//*[@id="mando"]/div[2]/div/div/div[2]/form/div[1]/div/div[6]/button/span
    Sleep    2s
    captureEntirePageScreenshot
    click    xpath=//*[@id="mando"]/div[2]/div/div/div[2]/form/div[1]/div/div[6]/button/span
    Sleep    4s
    #---Resultado
    sendKeys    //*[@id="mando"]/div[3]/div/div/div[1]/div/div[1]/div/strong    PAGE_DOWN
    captureEntirePageScreenshot
    Sleep    2s
    #---Seleccion de registro
    click    xpath=//*[@id="dropdownOpciones"]/button
    Sleep    3s
    captureEntirePageScreenshot
    Mouse Over    link=Gestionar Solicitud
    Sleep    1s
    click    link=Gestionar Solicitud
    Sleep    5s
    captureEntirePageScreenshot
    #---------------------------------------------------------------
    click    link=Residentes
    #---Gestion Solicitud
    click    id=run_encuestador
    type    id=run_encuestador    12903207
    Sleep    1s
    click    id=enc_dv
    type    id=enc_dv    3
    Sleep    1s
    click    id=fecha_encuesta
    click    xpath=//tr[3]/td[4]
    Sleep    1s
    captureEntirePageScreenshot
    #---Datos educacion
    click    id=icobtn_13914869_educacion
    Sleep    2s
    click    xpath=(//button[@type='button'])[7]
    click    xpath=//form[@id='formgrilla']/div[4]/ul
    click    id=icobtn_13914869_educacion
    Sleep    1s
    click    xpath=//*[@id="div_13914869_e1"]/div/div[2]/div/label
    #---E2 ¿Por qué no asiste? O ¿por qué no lo hace?
    click    xpath=//*[@id="13914869_e2"]
    click    //option[contains(text(),"11. Terminó de estudiar")]
    Sleep    1s
    #---E4 ¿En qué tipo de educación llegó hasta este curso?
    click    xpath=//*[@id="13914869_e4"]
    click    //option[contains(text(),"11. Instituto Profesional Completa")]
    Sleep    1s
    #---E3 ¿Hasta qué curso llegó?
    click    xpath=//*[@id="13914869_e3"]
    click    //option[contains(text(),"Cuarto Año")]
    Sleep    1s
    captureEntirePageScreenshot
    click    id=btn_guardar_educacion13914869
    #---------------------------------------------------------------
    click    xpath=//*[@id="pi"]
    click    //option[contains(text(),"0. No pertenece a ninguno de ellos")]
    Sleep    1s
    #---------------------------------------------------------------
    #---Datos Salud
    click    id=icobtn_13914869_salud
    Sleep    1s
    #---S1. ¿Esta persona tiene alguna de las siguientes condiciones de forma permanente?
    click    xpath=//div[@id='div_13914869_s1']/div[2]/div/label
    click    xpath=//div[@id='div_13914869_s2a']/div/div/label
    click    xpath=//div[@id='div_13914869_s2b']/div/div/label
    click    xpath=//div[@id='div_13914869_s2c']/div/div/label
    click    xpath=//div[@id='div_13914869_s2d']/div/div/label
    click    xpath=//div[@id='div_13914869_s2e']/div/div/label
    captureEntirePageScreenshot
    click    id=btn_guardar_salud13914869
    #---------------------------------------------------------------
    #---Datos Ingresos
    click    id=icobtn_13914869_ingreso
    Sleep    1s
    #---O1. ¿Está trabajando?
    click    xpath=//*[@id="div_13914869_o1"]/div/div[1]/div/label
    #---O2. ¿Cuál es su ocupación actual?
    click    xpath=//*[@id="13914869_o2"]
    click    //option[contains(text(),"3. Empleado u obrero")]
    Sleep    1s
    #---O3. Códigos Rama
    click    xpath=//*[@id="13914869_o3"]
    click    //option[contains(text(),"5.Construcción")]
    Sleep    1s
    #---O4. Temporalidad / Permanencia
    click    xpath=//*[@id="13914869_o4"]
    click    //option[contains(text(),"1.Permanente")]
    Sleep    1s
    #---O5. Código Contrato
    click    xpath=//*[@id="div_13914869_o5"]/div/div[1]/div/label
    Sleep    1s
    captureEntirePageScreenshot
    #---O6. Códigos Relación Contractual
    click    xpath=//*[@id="13914869_o6"]
    click    //option[contains(text(),"1.Plazo indefinido")]
    Sleep    1s
    #---O7. ¿Cuántas horas a la semana trabaja?
    click    id=13914869_o7
    type    id=13914869_o7    45
    Sleep    1s
    #---Y3. Otros ingresos anuales
    click    id=13914869_y3
    type    id=13914869_y3    0
    Sleep    1s
    #---
    captureEntirePageScreenshot
    click    id=btn_guardar_ingreso13914869
    #---------------------------------------------------------------
    #---Datos Ingresos Mensuales
    click    xpath=//*[@id="btn_13914869_ingreso_mensual"]
    Sleep    2s
    #---Ingresos del trabajo
    click    id=13914869_y1_m1
    type    id=13914869_y1_m1    ${INGRESO460}
    click    id=13914869_y1_m7
    type    id=13914869_y1_m7    ${INGRESO460}
    Sleep    1s
    click    id=13914869_y1_m2
    type    id=13914869_y1_m2    ${INGRESO460}
    click    id=13914869_y1_m8
    type    id=13914869_y1_m8    ${INGRESO460}
    Sleep    1s
    click    id=13914869_y1_m3
    type    id=13914869_y1_m3    ${INGRESO460}
    click    id=13914869_y1_m9
    type    id=13914869_y1_m9    ${INGRESO460}
    Sleep    1s
    click    id=13914869_y1_m4
    type    id=13914869_y1_m4    ${INGRESO460}
    click    id=13914869_y1_m10
    type    id=13914869_y1_m10    ${INGRESO460}
    Sleep    1s
    click    id=13914869_y1_m5
    type    id=13914869_y1_m5    ${INGRESO460}
    click    id=13914869_y1_m11
    type    id=13914869_y1_m11    ${INGRESO460}
    Sleep    1s
    click    id=13914869_y1_m6
    type    id=13914869_y1_m6    ${INGRESO460}
    click    id=13914869_y1_m12
    type    id=13914869_y1_m12    ${INGRESO460}
    captureEntirePageScreenshot
    #---Jubilaciones
    click    id=13914869_y2_m1
    type    id=13914869_y2_m1    ${INGRESO0}
    click    id=13914869_y2_m7
    type    id=13914869_y2_m7    ${INGRESO0}
    Sleep    1s
    click    id=13914869_y2_m2
    type    id=13914869_y2_m2    ${INGRESO0}
    click    id=13914869_y2_m8
    type    id=13914869_y2_m8    ${INGRESO0}
    Sleep    1s
    click    id=13914869_y2_m3
    type    id=13914869_y2_m3    ${INGRESO0}
    click    id=13914869_y2_m9
    type    id=13914869_y2_m9    ${INGRESO0}
    Sleep    1s
    click    id=13914869_y2_m4
    type    id=13914869_y2_m4    ${INGRESO0}
    click    id=13914869_y2_m10
    type    id=13914869_y2_m10    ${INGRESO0}
    Sleep    1s
    click    id=13914869_y2_m5
    type    id=13914869_y2_m5    ${INGRESO0}
    click    id=13914869_y2_m11
    type    id=13914869_y2_m11    ${INGRESO0}
    Sleep    1s
    click    id=13914869_y2_m6
    type    id=13914869_y2_m6    ${INGRESO0}
    click    id=13914869_y2_m12
    type    id=13914869_y2_m12    ${INGRESO0}
    captureEntirePageScreenshot
    #---
    click    id=btn_guardar_ingreso_mensual13914869
    #---------------------------------------------------------------
    #---Datos Vivienda
    click    link=Vivienda
    Sleep    2s
    click    xpath=//*[@id="v1"]
    click    //option[contains(text(),"8. Hospedería")]
    captureEntirePageScreenshot
    #---------------------------------------------------------------
    #---Datos Localizacion
    click    xpath=//*[@id="formgrilla"]/div[4]/ul/li[3]/a
    Sleep    2s
    captureEntirePageScreenshot
    #---------------------------------------------------------------
    #---Datos Adjuntos
    click    link=Adjuntos
    Sleep    2s
    captureEntirePageScreenshot
    sendKeys    xpath=//*[@id="adjuntos"]/div/div[2]/div/div/div/object/div/div[1]/h3    PAGE_DOWN
    Sleep    1s
    captureEntirePageScreenshot
    Mouse Over    xpath=//*[@id="formgrilla"]/div[5]/button[3]
    #---BOTON GUARDAR COMENTADO
    #click    xpath=//*[@id="formgrilla"]/div[5]/button[3]
    click    xpath=//*[@id="volver"]
    Sleep    2s
    captureEntirePageScreenshot
    click    xpath=//html/body/div[3]/div/div[3]/button[1]
    #---------------------------------------------------------------
    Sleep    4s
    click    link=Cerrar sesión
    captureEntirePageScreenshot
    close
    [Teardown]    Close Browser
