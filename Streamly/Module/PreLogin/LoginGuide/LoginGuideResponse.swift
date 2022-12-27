//
//  LoginGuideResponse.swift
//  Streamly
//
//  Created by tushar mehta on 26/12/22.
//  Copyright © 2022 Rohit Singh. All rights reserved.
//

import Foundation
struct LoginGuideViewModel : Codable {
    let success : Bool?
    let count : Int?
    let data : [LoginGuideViewModelData]?
    let response_code : Int?
    
    enum CodingKeys: String, CodingKey {
        
        case success = "success"
        case count = "count"
        case data = "data"
        case response_code = "response_code"
    }
}

struct LoginGuideViewModelData : Codable {
    let sno : Int?
    let image : String?
    let title : String?
    let description : String?
    
    enum CodingKeys: String, CodingKey {
        
        case sno = "sno"
        case image = "image"
        case title = "title"
        case description = "description"
    }
}
