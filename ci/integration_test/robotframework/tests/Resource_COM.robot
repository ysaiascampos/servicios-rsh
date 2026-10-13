*** Settings ***
Library           SeleniumLibrary
Library           OperatingSystem

*** Variables ***
#----------------------------VARIABLES GENERALES-------------------------------------
${BROWSER}        chrome    # Navegador
${SELSPEED}       0.0s
${URL}            https://rshmunicipal.mds.cl/    # Web de entrada
${TIMEOUT_MS}     1000    # 1000 ms \ -> 1 seg.
#----------------------------VARIABLES FRANCISCO FLORES------------------------------
@{USER}           83509198    # Rut Usuario.
@{PASSWORDS}      SINPASSWORD    # Password de Usuario.
@{RUTCIUDADANOS}    13722911    19289020    5316604    12047944    18739954    26611358    14399742    # Rut de los ciudadanos.
@{DV_CIUDADANOS}    0    9    K    8    7    1    # Digito Verificador de los ciudadanos.
${UPLOAD_DUMMY_DOCX_FILE}    ${EMPTY}    # Archivo docx.
#------------------------------------------------------------------------------------

*** Keywords ***
scrollView
    [Arguments]    ${element}
    scroll element into view    ${element}

deleteAllVisibleCookiesAndWait
    Delete All Cookies

close
    Close Browser

captureEntirePageScreenshot
    Capture Page Screenshot    ${TEST_NAME}_{index}.png

selectByValue
    [Arguments]    ${element}    ${value}
    Select From List by Value    ${element}    ${value}

open
    [Arguments]    ${element}
    Go To    ${element}

clickAndWait
    [Arguments]    ${element}
    Click Element    ${element}

click
    [Arguments]    ${element}
    Click Element    ${element}

sendKeys
    [Arguments]    ${element}    ${value}
    Press Keys    ${element}    ${value}

submit
    [Arguments]    ${element}
    Submit Form    ${element}

type
    [Arguments]    ${element}    ${value}
    Input Text    ${element}    ${value}

selectAndWait
    [Arguments]    ${element}    ${value}
    Select From List    ${element}    ${value}

select
    [Arguments]    ${element}    ${value}
    Select From List    ${element}    ${value}

verifyValue
    [Arguments]    ${element}    ${value}
    Element Should Contain    ${element}    ${value}

verifyText
    [Arguments]    ${element}    ${value}
    Element Should Contain    ${element}    ${value}

verifyElementPresent
    [Arguments]    ${element}
    Page Should Contain Element    ${element}

verifyVisible
    [Arguments]    ${element}
    Page Should Contain Element    ${element}

verifyTitle
    [Arguments]    ${title}
    Title Should Be    ${title}

verifyTable
    [Arguments]    ${element}    ${value}
    Element Should Contain    ${element}    ${value}

assertConfirmation
    [Arguments]    ${value}
    Alert Should Be Present    ${value}

assertText
    [Arguments]    ${element}    ${value}
    Element Should Contain    ${element}    ${value}

assertValue
    [Arguments]    ${element}    ${value}
    Element Should Contain    ${element}    ${value}

assertElementPresent
    [Arguments]    ${element}
    Page Should Contain Element    ${element}

assertVisible
    [Arguments]    ${element}
    Page Should Contain Element    ${element}

assertTitle
    [Arguments]    ${title}
    Title Should Be    ${title}

assertTable
    [Arguments]    ${element}    ${value}
    Element Should Contain    ${element}    ${value}

waitForText
    [Arguments]    ${element}    ${value}
    Element Should Contain    ${element}    ${value}

waitForValue
    [Arguments]    ${element}    ${value}
    Element Should Contain    ${element}    ${value}

waitForElementPresent
    [Arguments]    ${element}
    Page Should Contain Element    ${element}

waitForVisible
    [Arguments]    ${element}
    Page Should Contain Element    ${element}

waitForTitle
    [Arguments]    ${title}
    Title Should Be    ${title}

waitForTable
    [Arguments]    ${element}    ${value}
    Element Should Contain    ${element}    ${value}

doubleClick
    [Arguments]    ${element}
    Double Click Element    ${element}

doubleClickAndWait
    [Arguments]    ${element}
    Double Click Element    ${element}

goBack
    Go Back

goBackAndWait
    Go Back

runScript
    [Arguments]    ${code}
    Execute Javascript    ${code}

runScriptAndWait
    [Arguments]    ${code}
    Execute Javascript    ${code}

setSpeed
    [Arguments]    ${value}
    Set Selenium Timeout    ${value}

setSpeedAndWait
    [Arguments]    ${value}
    Set Selenium Timeout    ${value}

verifyAlert
    [Arguments]    ${value}
    Alert Should Be Present    ${value}

logConsole
    [Arguments]    ${msg}
    Log    ${msg}    console=yes
    #Alert Should Be Present    ${value}

Scroll Page To Location
    [Arguments]    ${x_location}    ${y_location}
    Execute JavaScript    window.scrollTo(${x_location},${y_location})

mainSetup
    Run Keywords    Open Browser    ${URL}    ${BROWSER}
    ...    AND    Maximize Browser Window
    ...    AND    Set Selenium Speed    ${SELSPEED}
    Set Global Variable    ${UPLOAD_DUMMY_DOCX_FILE}    ${CURDIR}/doc_prueba.pdf
    logConsole    \nArchivo dummy: ${UPLOAD_DUMMY_DOCX_FILE}

LoginToRSH
    [Arguments]    ${user}    ${passwords}
    [Documentation]    *Pasos funcionales para ingresar a RSH*
    #logConsole    "Iniciando login RSH: ${TESTNAME}"
    captureEntirePageScreenshot
    click    id=login-username
    type    id=login-username    ${user}
    click    id=login-password
    type    id=login-password    ${passwords}
    captureEntirePageScreenshot
    click    xpath=//*[@id="loginform"]/div[3]/div/button

CerrarSesion
    [Documentation]    *Cerrar Sesión Sistema RSH*
    captureEntirePageScreenshot
    click    link=Cerrar sesión
    Sleep    5s
    close
