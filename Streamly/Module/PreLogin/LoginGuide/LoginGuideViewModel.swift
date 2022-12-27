//
//  LoginGuideViewModel.swift
//  Streamly
//
//  Created by tushar mehta on 23/12/22.
//  Copyright © 2022 Rohit Singh. All rights reserved.
//

import Foundation




class LoginGuideParserContact: NSObject  {

    var contactData1:((LoginGuideViewModel)->())?
    weak var delegate:HandleError?

    
    func getLoginGuideData() {
        ApiCall.getLoginGuideVcDetail(success: { data, _
            in
            if let contactModel = loginGuideParser.parseLoginGuidetData(data!) {
                self.contactData1!((contactModel))
                print("chek count\(contactModel.count)")
                print("chek count4433\(self.contactData1)")

                //debugPrint(contactModel)
            }
            //debugPrint("Success")
        }) { (error, data, code)  in
            if let `data` = data, let failedData: FailedResponse = DataParser.parseReponseToModel(data) {
                self.delegate?.handleError(failedData.message ?? "", failedData.response_code ?? 0)
                return
            }
            //debugPrint("failure")
            self.delegate?.handleError(error?.localizedDescription ?? "",code ?? 0)
        }
    }
    
    

}
class loginGuideParser{
    static func parseLoginGuidetData(_ data: Data) -> LoginGuideViewModel? {
        print("97979797\(data)")
        do {
            let contactModelElement = try JSONDecoder().decode(LoginGuideViewModel.self, from: data)
            print("ititi6868655555558")

            
            return contactModelElement
        } catch {
            print("tututuu\(error)")
            print("ititi686868")
            //debugPrint(error)
        }
        return nil
    }
}
