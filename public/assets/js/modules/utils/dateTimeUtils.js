/**
 * @rief Parse time string to get given time in seconds
 * @param timeStr
 * @param formatDelimiter
 */
export function getSecondsFromTimeString(timeStr, formatDelimiter) {
    const MAX_TIME_COMPONENT_LENGTH = 2
    const timeComponents = timeStr.split(formatDelimiter)

    let seconds = 0
    timeComponents.reverse().forEach((timeComponent, index) => {
        const intTimeComponent = parseInt(timeComponent)

        // Check integrity of timeStr
        if (isNaN(intTimeComponent) || index > MAX_TIME_COMPONENT_LENGTH || intTimeComponent < 0)
            // Workarounds only until TypeScript arrives //
            throw "Invalid time parameter given - expected e.g. 'hh:mm:ss'"

        // Seconds & Minutes format                 // Hours format
        if ((index <= 1 && intTimeComponent > 60) || (index == 2 && intTimeComponent > 24))
            // Workarounds only until TypeScript arrives... again //
            throw "Invalid time parameter given - expected e.g. 'hh:mm:ss'"


        // From the back: seconds*1 + minutes * 60 + hours * 3600
        seconds += Math.pow(60, index) * parseInt(timeComponent)
    })

    return seconds
}


/**
 * @rief Parse time string to get given time in seconds
 * @param timeStr
 * @param formatDelimiter
 */
export function getMilisecondsFromTimeString(timeStr, formatDelimiter) {
    const MAX_TIME_COMPONENT_LENGTH = 2
    const timeComponents = timeStr.split(formatDelimiter)

    let ms = 0
    timeComponents.reverse().forEach((timeComponent, index) => {
        const intTimeComponent = parseInt(timeComponent)

        // Check integrity of timeStr
        if (isNaN(intTimeComponent) || index > MAX_TIME_COMPONENT_LENGTH || intTimeComponent < 0)
            // Workarounds only until TypeScript arrives //
            throw "Invalid time parameter given - expected e.g. 'hh:mm:ss'"

        // Seconds & Minutes format                 // Hours format
        if ((index <= 1 && intTimeComponent > 60) || (index == 2 && intTimeComponent > 24))
            // Workarounds only until TypeScript arrives... again //
            throw "Invalid time parameter given - expected e.g. 'hh:mm:ss'"


        // From the back: (seconds*1 + minutes * 60 + hours * 3600) * 1000ms
        ms += Math.pow(60, index) * parseInt(timeComponent) * 1000
    })

    return ms
}


/**
 * @rief Parse the time components from a given time
 * @param timeStr
 * @param formatDelimiter
 * @return {object} - hours, minutes, seconds
 *
 * @bug NO ERROR CHECKING - use only with DB generated time
 */
export function getTimeComponentsFromTimeString(timeStr, formatDelimiter) {
    const timeComponents = timeStr.split(formatDelimiter)
    return {
        hours: parseInt(timeComponents[0]),
        minutes: parseInt(timeComponents[1]),
        seconds: parseInt(timeComponents[2])
    }
}


/**
 * @brief Format date object into format used by AuctionSystem
 * @param datetime
 * @returns {string}
 */
export function formatDateTime(datetime)
{
    let day = datetime.getDay()
    if (day < 10) day = `0${day}`

    let month = datetime.getMonth() + 1
    if (month < 10) month = `0${month}`

    const year = datetime.getFullYear()

    let hours = datetime.getHours()
    if (hours < 10) hours = `0${hours}`

    let minutes = datetime.getMinutes()
    if (minutes < 10) minutes = `0${minutes}`

    let seconds = datetime.getSeconds()
    if (seconds < 10) seconds = `0${seconds}`

    const formattedDateTimeString = `${day}.${month}.${year} ${hours}:${minutes}:${seconds}`
    return formattedDateTimeString
}


/**
 * @brief Add time to date without changing passed arguments - timeString must be in format 'HH:MM:SS' !!
 * @param date
 * @param timeString
 * @returns {Date}
 */
export function dateAddTime(date, timeString)
{
    const timeInMs = getMilisecondsFromTimeString(timeString, ":")
    const result = new Date()
    result.setTime(date.getTime() + timeInMs)

    return result
}