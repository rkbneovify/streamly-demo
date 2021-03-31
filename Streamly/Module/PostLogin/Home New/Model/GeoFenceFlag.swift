//
//  GeoFenceFlag.swift
//  Streamly
//
//  Created by Rohit Singh on 8/7/20.
//  Copyright © 2020 Rohit Singh. All rights reserved.
//

import Foundation

struct GeoFenceAddress : Codable {

    let success:Bool?
    let message:String?
    let response_code:Int?
    let popupmsg:String?
    let popupmsgtoggle:Bool?


    enum CodingKeys: String, CodingKey {

        case message = "message"
        case success = "success"
        case response_code = "response_code"
        case  popupmsg = "popupmsg"
        case popupmsgtoggle = "popupmsgtoggle"

    }

    init(from decoder: Decoder) throws {
        let values = try decoder.container(keyedBy: CodingKeys.self)
        message = try values.decodeIfPresent(String.self, forKey: .message)
        popupmsg = try values.decodeIfPresent(String.self, forKey: .popupmsg)
        response_code = try values.decodeIfPresent(Int.self, forKey: .response_code)
        success = try values.decodeIfPresent(Bool.self, forKey: .success)
        popupmsgtoggle = try values.decodeIfPresent(Bool.self, forKey: .popupmsgtoggle)

    }
}
