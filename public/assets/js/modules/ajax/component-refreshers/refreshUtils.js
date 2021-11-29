import "core-js/stable";
import "regenerator-runtime/runtime";

import axios from "axios"
import { $exists } from "../../utils/jQueryElementUtils"
import { AJAX_REFRESH_COMPONENTS_INTERVAL } from "../ajaxConfig"


/**
 * @brief Register a component type to refresh inside of a component wrapper
 *        Standard refresh functionality used
 * @param componentWrapper CSS-className
 * @param componentRefreshFnParams Array
 * @param interval
 */
export function registerStandardComponentsRefresh(componentWrapper, // Duplicit wrapper pass
                                          componentRefreshFnParams,
                                          interval=AJAX_REFRESH_COMPONENTS_INTERVAL)
{
    if ($exists(componentWrapper))
    {
        setInterval(function() {
            standardComponentRefresh(...componentRefreshFnParams)
        }, interval)
    }
}


/**
 * @brief Register a component type to refresh inside of a component wrapper
 *        Accepts custom refresh functionality
 * @param componentWrapper CSS-className
 * @param componentRefreshFn Callback
 * @param componentRefreshFnParams Array
 * @param interval
 */
export function registerCustomComponentsRefresh(componentWrapper,
                                                componentRefreshFn,
                                                componentRefreshFnParams,
                                                interval=AJAX_REFRESH_COMPONENTS_INTERVAL)
{
    if (componentRefreshFn === null)
        componentRefreshFn = standardComponentRefresh

    if ($exists(componentWrapper))
    {
        setInterval(function() {
            componentRefreshFn(...componentRefreshFnParams)
        }, interval)
    }
}


/**
 * @brief Register a component type to update itself inside of it's wrapper
 *        Standard update functionality used
 * @param componentWrapper CSS-className
 * @param componentUpdateFnParams Array
 * @param interval
 */
export function registerStandardComponentUpdate(componentWrapper, // Duplicit wrapper pass
                                                componentUpdateFnParams,
                                                interval=AJAX_REFRESH_COMPONENTS_INTERVAL)
{
    if ($exists(componentWrapper))
    {
        setInterval(function() {
            standardComponentUpdate(...componentUpdateFnParams)
        }, interval)
    }
}


/**
 * @brief Fetch component records from specified application endpoint
 * @param endpoint
 * @return {Promise<*>}
 */
export async function fetchComponentRecords(endpoint)
{
    const componentData = await axios(endpoint)
    return componentData.data.data
}


/**
 * @brief Filter given component records based on whether they are already in view
 *        as rendered components (determined by componentClass parameter)
 * @param componentRecords
 * @param componentClass
 * @return {*}
 */
export function filterDuplicateComponentsByRecord(componentRecords, componentClass)
{
    // Get existing components
    const existingComponents = Array.from($(`${componentClass.elementClass}`))

    // Extract their IDs
    const existingComponentIDs = existingComponents.map(comp => parseInt(comp.dataset.id))

    // Filter out the already existing components by ID
    const newComponents = componentRecords.filter(
        componentRecord => !existingComponentIDs.includes(componentRecord.id)
    )

    return newComponents
}


/**
 * @brief Filter empty records
 * @param componentRecords Array
 * @return {*}
 */
export function filterEmptyComponentsByRecord(componentRecords)
{
    if (!!componentRecords) return null

    const filtered = componentRecords.filter(componentRecord => componentRecord != null)
    return filtered
}


export function filterDuplicateComponentsByComponent()
{
}


/**
 * @brief Determine whether a componet with specific class is in the view
 * @param component
 * @param componentClass
 * @return {boolean}
 */
export function isComponentInView(component, componentClass)
{
    const targetComponent = $(`${componentClass.elementClass}[data-id=${component.params.id}]`)
    if (!$exists(targetComponent))
        return false
    return true
}


/**
 * @brief Prepare instantiated components array from component records array
 * @param componentRecords
 * @return {*}
 */
export function componentsFromCompoentRecords(componentRecords, componentInstantiatingClass) {
    return componentRecords.reduce(
        (accumulatedComponents, newComponent) => { // Build new array from
            return [
                ...accumulatedComponents,
                // Instantiate new Component
                componentInstantiatingClass.fromDbRecord(newComponent)
            ]
        }, []
    );
}


/**
 * @brief Preppend component into it's wrapper safely (check duplicates)
 * @param components
 * @param componentsClass
 * @param componentsWrapper
 */
export function safePrependComponentsIntoWrapper(components, componentsClass, componentsWrapper)
{
    for (const component of components)
    {
        // Final duplicity check
        if (!isComponentInView(component, componentsClass))
        {
            // Add into view
            componentsWrapper.prepend(
                component.render()
            )
        }
    }
}


/**
 * @brief Append component into wrapper
 * @param component
 * @param componentWrapper
 */
export function addComponent(component, componentWrapper)
{
    componentWrapper.append(component.render())
}


/**
 * @brief Re-render updated component
 * @param component
 * @param componentWrapper
 */
export function updateComponent(component, componentWrapper)
{
    componentWrapper.children().remove()
    componentWrapper.append(component.render())
}


/**
 * @brief Standard component refresh process - add new, remove deleted (todo)
 *        Use strictly only for list components, not singular
 * @param componentClass
 * @param componentWrapper
 * @param endpoint
 * @return {Promise<void>}
 */
export async function standardComponentRefresh(componentClass, componentWrapper, endpoint)
{
    // Fetch all component records
    const componentRecords = filterEmptyComponentsByRecord( await fetchComponentRecords(endpoint) )
    const componentRecordsNotNull = !!componentRecords
    if (!componentRecordsNotNull) return

    const uniqueComponentRecords = filterDuplicateComponentsByRecord(componentRecords,componentClass)
    const uniqueRecordsNotNull = !!uniqueComponentRecords
    if (!uniqueRecordsNotNull) return

    // Instantiate components from all the unique components
    const uniqueComponents = componentsFromCompoentRecords(
        Object.values(uniqueComponentRecords),
        componentClass // InstantiatingClass
    )
    safePrependComponentsIntoWrapper(uniqueComponents, componentClass, componentWrapper)

    // TODO CODE REMOVE_DELETED
}


/**
 * @brief Update specific component inside of its wrapper (fetch it's data and re-render it)
 * @param componentClass
 * @param componentWrapper
 * @param endpoint
 */
export async function standardComponentUpdate(componentClass, componentWrapper, endpoint)
{
    const componentRecord = await fetchComponentRecords(endpoint)
    const componentRecordNotNull = !!componentRecord
    if (!componentRecordNotNull) return

    const component = componentClass.fromDbRecord(componentRecord)

    if ($exists(componentWrapper.children(componentClass.elementClass)))
        updateComponent(component, componentWrapper)
    else
        addComponent(component, componentWrapper)
}