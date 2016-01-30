<?php
namespace app\library\dry;
class dry
    {
        static public function setValue($value, $elseValue = '')
            {
                return $value ? $value : $elseValue;
            }

        static public function inc($collection)
            {
                return 'function ()
        {
        var ret = db.inc.findAndModify({
        query: { field_id: "' . $collection . '" },
        update: { $inc: { nextId: 1 } },
        new: true
        });
        return ret.nextId;
        }';
            }
    }