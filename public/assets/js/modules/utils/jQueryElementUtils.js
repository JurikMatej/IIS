/**
 * @brief Determine whether element (jquery collection) exists
 * @param $collection
 * @returns {boolean}
 */
export function $exists($collection)
{
    return !!$collection.length
}
