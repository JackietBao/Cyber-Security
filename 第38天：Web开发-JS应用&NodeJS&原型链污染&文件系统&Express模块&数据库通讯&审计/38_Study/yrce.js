// // foo是一个简单的JavaScript对象
// let foo = {bar: 1}
//
// // foo.bar 此时为1
// console.log(foo.bar)
//
// // 修改foo的原型（即Object）
// foo.__proto__.bar = 2
//
// // 由于查找顺序的原因，foo.bar仍然是1
// console.log(foo.bar)
//
// // // 此时再用Object创建一个空的zoo对象
//  let zoo = {}
// //
// // // 查看zoo.bar，此时bar为2
//  console.log(zoo.bar)


let foo = {bar: 1}

console.log(foo.bar)

foo.__proto__.bar = 'require(\'child_process\').execSync(\'calc/\');'

console.log(foo.bar)

let zoo = {}

console.log(eval(zoo.bar))