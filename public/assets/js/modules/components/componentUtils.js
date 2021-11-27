/** @const Path to static assets */
export const PATH_ASSETS = "../../assets"
/** @const Path to static image assets */
export const PATH_IMG_ASSETS = `${PATH_ASSETS}/images`


/**
 * Create singular HTMLElement
 *
 * @param html
 * @returns {ChildNode}
 */
export function createComponent(html)
{
    const parent = document.createElement("div")
    parent.innerHTML = html

    return parent.childNodes[1] // idx 0 belongs to #text element
}


/**
 * @brief Get path to static assets
 * @returns {string}
 */
export function getAssetsPath()
{
    return PATH_ASSETS
}


/**
 * @brief Get path to static image assets
 * @returns {string}
 */
export function getImageAssetsPath()
{
    return PATH_IMG_ASSETS
}